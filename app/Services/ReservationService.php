<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Enums\UserRole;
use App\Jobs\Reservation\GenerateReservationPDF;
use App\Mail\Reservation\Cancelled;
use App\Mail\reservation\Expire;
use App\Mail\Reservation\NoShow;
use App\Mail\reservation\Received;
use App\Mail\reservation\ReceivedFunctionHall;
use App\Mail\Reservation\Updated;
use App\Models\CancelledReservation;
use App\Models\FunctionHallReservations;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReservationService
{
    public $handlers;

    public function __construct() {
        $this->handlers =collect([
            'amenity' => new AmenityService,
            'room' => new RoomService,
            'service' => new AdditionalServiceHandler,
            'billing' => new BillingService,
            'car' => new CarService,
        ]);
    }

    public function create($data) {
        $reservation = DB::transaction(function () use ($data) {
            // Assuming the $data is already validated prior to this point
            // Generate password for guest
            $password = ucwords(strtolower($data['last_name'])) . now()->format('Y') . '!';

            // Check if the guest already have an account
            $user = User::where('email', $data['email'] ?? '')->firstOrCreate([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'role' => UserRole::GUEST,
                'address' => $data['address'],
                'password' => $password,
            ]);

            $expires_at = Carbon::now()->addHour();
            $status = ReservationStatus::AWAITING_PAYMENT->value;
            $proof_image_path = null;
    
            // Store proof of payment to payments folder
            if ((int) Arr::get($data, 'downpayment', 0) > 0 || !empty($data['proof_image_path'])) {
                if (!empty($data['proof_image_path'])) {
                    $proof_image_path = $data['proof_image_path']->store('payments', 'public');
                }
                
                $expires_at = null; 
                $status = ReservationStatus::PENDING->value;
            }
    
            // Create the reservation
            $reservation = $user->reservations()->create([
                'date_in' => Arr::get($data, 'date_in'),
                'date_out' => Arr::get($data, 'date_out'),
                'senior_count' => Arr::get($data, 'senior_count'),
                'pwd_count' => Arr::get($data, 'pwd_count'),
                'adult_count' => Arr::get($data, 'adult_count'),
                'children_count' => Arr::get($data, 'children_count'),
                'note' => Arr::get($data, 'note', null),
                'expires_at' => $expires_at,
                'status' => $status,
            ]);
    
            // Attach the rooms to reservation
            if (isset($data['selected_rooms'])) {
                $this->handlers->get('room')->attach($reservation, $data['selected_rooms']);
            }
    
            // Attach amenities to reservation
            if (isset($data['selected_amenities'])) {
                $this->handlers->get('amenity')->attach($reservation, $data['selected_amenities']);
            }
    
            // Attach services to reservation
            if (isset($data['selected_services'])) {
                $this->handlers->get('service')->attach($reservation, $data['selected_services']);
            }
    
            // Store cars for park reservation
            if (isset($data['cars'])) {
                $this->handlers->get('car')->create($reservation, $data['cars']);
            }
    
            // Compute breakdown
            $breakdown = $this->handlers->get('billing')->breakdown($reservation);
            $breakdown['downpayment'] = Arr::get($data, 'downpayment', 0);
    
            // Create the invoice
            $invoice = $this->handlers->get('billing')->create($reservation, $breakdown);
    
            // Create the downpayment
            if ((int) Arr::get($data, 'downpayment', 0) > 0 || !empty($data['proof_image_path'])) {
                $invoice->payments()->create([
                    'proof_image_path' => $proof_image_path,
                    'amount' => Arr::get($data, 'downpayment', 0),
                    'payment_method' => Arr::get($data, 'payment_method', 'gcash'),
                    'transaction_id' => Arr::get($data, 'transaction_id', null),
                    'purpose' => 'downpayment',
                    'payment_date' => now(),
                ]);
            }
    
            // Generate PDF
            GenerateReservationPDF::dispatch($reservation);
    
            // Send confirmation email to the guest
            Mail::to($reservation->user->email)->queue(new Received($reservation));
            return $reservation;
        });

        return $reservation;
    }

    public function update(Reservation $reservation, $data)
    {
        // Assuming the $data is already validated prior to this point
        $reservation->update([
            'date_in' => Arr::get($data, 'date_in', $reservation->date_in),
            'date_out' => Arr::get($data, 'date_out', $reservation->date_out),
            'adult_count' => Arr::get($data, 'adult_count', $reservation->adult_count),
            'children_count' => Arr::get($data, 'children_count', $reservation->children_count),
            'senior_count' => Arr::get($data, 'senior_count', $reservation->senior_count),
            'pwd_count' => Arr::get($data, 'pwd_count', $reservation->pwd_count),
            'first_name' => Arr::get($data, 'first_name'),
            'last_name' => Arr::get($data, 'last_name'),
            'phone' => Arr::get($data, 'phone'),
            'address' => Arr::get($data, 'address'),
            'note' => Arr::get($data, 'note'),
        ]);

        $reservation->save();

        if (isset($data['selected_rooms'])) {
            $this->handlers->get('room')->sync($reservation, $data['selected_rooms']);
        }
        if (isset($data['selected_services'])) {
            $this->handlers->get('service')->sync($reservation, $data['selected_services']);
        }
        if (isset($data['selected_amenities'])) {
            $this->handlers->get('amenity')->sync($reservation, $data['selected_amenities']);
        }
        if (isset($data['cars'])) {
            $this->handlers->get('car')->update($reservation, $data['cars']);
        }

        // Update the invoice
        $breakdown = $this->handlers->get('billing')->breakdown($reservation->fresh());
        $invoice_data = [
            'total_amount' => $breakdown['sub_total'],
            'downpayment' => $reservation->invoice->downpayment,
            'balance' => $breakdown['sub_total'] - $reservation->invoice->downpayment,
        ];
        $invoice_data['status'] = $invoice_data['balance'] > 0 ? InvoiceStatus::PARTIAL->value : InvoiceStatus::PAID->value;

        // Create the invoice
        $this->handlers->get('billing')->update($reservation->invoice, $invoice_data);

        // Send update email
        Mail::to($reservation->user->email)->queue(new Updated($reservation));

        return $reservation;
    }

    public function cancel(Reservation $reservation, $data) {
        DB::transaction(function () use ($reservation, $data) {
            $reservation->canceled_at = now();
            $reservation->status = ReservationStatus::CANCELED;
            $reservation->save();
    
            CancelledReservation::create([
                'reservation_id' => $reservation->id,
                'reason' => Arr::get($data, 'reason'),
                'canceled_by' => Arr::get($data, 'canceled_by'),
                'refund_amount' => Arr::get($data, 'refund_amount', 0),
                'canceled_at' => now(),
            ]);
    
            // Update amenity's quantity and room's availability tables
            $this->handlers->get('amenity')->release($reservation, $reservation->rooms);
            $this->handlers->get('room')->release($reservation, $reservation->rooms);
    
            // Send cancellation email to the guests
            Mail::to($reservation->user->email)->queue(new Cancelled($reservation));
        });
    }

    public function checkIn(Reservation $reservation) {
        DB::transaction(function () use ($reservation) {
            $reservation->status = ReservationStatus::CHECKED_IN;
            $reservation->save();

            foreach ($reservation->rooms as $room) {
                $room->pivot->status = ReservationStatus::CHECKED_IN;
                $room->pivot->save();
            }
        });
    }

    public function checkOut(Reservation $reservation, $selected_rooms) {
        DB::transaction(function () use ($reservation, $selected_rooms) {
            $rooms = $reservation->rooms()->whereIn('rooms.id', $selected_rooms->pluck('id'))->get();

            foreach ($rooms as $room) {
                $room->pivot->status = ReservationStatus::CHECKED_OUT->value;
                $room->pivot->save();
            }

            /**
             * If all rooms are checked out: 
             * - mark reservation as 'checked out'
             * - mark invoice as 'paid'
             */
            $checked_in_rooms = $reservation->fresh()->rooms()->where('room_reservations.status', ReservationStatus::CHECKED_IN)->count();

            if (empty($checked_in_rooms)) {
                $reservation->status = ReservationStatus::CHECKED_OUT->value;
                $reservation->save();
            }

            $this->handlers->get('amenity')->release($reservation->fresh(), $selected_rooms);
            $this->handlers->get('room')->release($reservation->fresh(), $selected_rooms);

            // Issue invoice if not yet issued
            if (empty($reservation->invoice->issue_date)) {
                $this->handlers->get('billing')->issueInvoice($reservation->invoice->fresh());
            }
        });
    }

    public function downloadPdf(Reservation $reservation) {
        $filename = $reservation->rid . ' - ' . strtoupper($reservation->user->last_name) . '_' . strtoupper($reservation->user->first_name) . '.pdf';
        $path = 'public/pdf/reservation/' . $filename;

        if (Storage::exists($path)) {
            return Storage::download($path, $filename);
        }

        GenerateReservationPDF::dispatch($reservation);
        return null;
    }

    public function confirm(Reservation $reservation, $data) {
        $reservation->status = ReservationStatus::CONFIRMED;
        $reservation->save();

        $filename = $reservation->rid . ' - ' . strtoupper($reservation->user->last_name) . '_' . strtoupper($reservation->user->first_name) . '.pdf';
        $path = 'public/pdf/reservation/' . $filename;

        if ($data['amount'] > 0) {
            $reservation->invoice->payments()->updateOrCreate(
                ['orid' => $data['orid']],
                [
                    'invoice_id' => $reservation->invoice->id,
                    'amount' => Arr::get($data, 'amount', 0),
                    'transaction_id' => Arr::get($data, 'transaction_id', null),
                    'payment_date' => Arr::get($data, 'payment_date', null),
                    'purpose' => 'downpayment',
                ]
                );

            $reservation->invoice->balance -= Arr::get($data, 'amount', 0);
            $reservation->invoice->status = $reservation->invoice->balance > 0 ? InvoiceStatus::PARTIAL : InvoiceStatus::PAID;
            $reservation->invoice->save();
        }

        if (!Storage::exists($path)) {
            GenerateReservationPDF::dispatch($reservation);
        }

        return $reservation;
    }

    public function expire(Reservation $reservation) {
        // Update the status of the reservation
        $reservation->status = ReservationStatus::EXPIRED;
        $reservation->save();

        // Send email to guests with expired reservations
        Mail::to($reservation->user->email)->queue(new Expire($reservation));

        $this->handlers->get('room')->sync($reservation, null);
        $this->handlers->get('amenity')->sync($reservation, null);
        $this->handlers->get('billing')->cancel($reservation->invoice);

        return $reservation;
    }

    public function reactivate(Reservation $reservation) {
        $reservation->status = ReservationStatus::AWAITING_PAYMENT->value;
        $reservation->expires_at = Carbon::now()->addHour();
        $reservation->save();

        return $reservation;
    }

    public function noShow(Reservation $reservation) {
        $reservation->status = ReservationStatus::NO_SHOW;
        $reservation->save();

        // Send email to guests with no-show reservations
        Mail::to($reservation->user->email)->queue(new NoShow($reservation));

        $this->handlers->get('room')->sync($reservation, null);
        $this->handlers->get('amenity')->sync($reservation, null);

        return $reservation;
    }

    public function delete(Reservation $reservation) {
        $this->handlers->get('room')->sync($reservation, null);
        $this->handlers->get('amenity')->sync($reservation, null);
        $this->handlers->get('service')->sync($reservation, null);

        $reservation->delete();
    }

    public function reschedule(Reservation $reservation, $data) {
        DB::transaction(function () use ($reservation, $data) {
            // Copy the old reservation to a new reservation
            $new_reservation = $reservation->replicate(['rid']);
            $new_reservation->date_in = $data['date_in'];
            $new_reservation->date_out = $data['date_out'];
            $new_reservation->rescheduled_from = $reservation->id;
            $new_reservation->status = $reservation->status;
            $new_reservation->save();

            // Attach rooms and services to the new reservation
            $this->handlers->get('room')->attach($new_reservation, $data['selected_rooms']);
            $this->handlers->get('service')->attach($new_reservation, $reservation->services);

            // Create a new invoice for the new reservation
            $breakdown = $this->handlers->get('billing')->breakdown($new_reservation->fresh());
            $new_invoice = $this->handlers->get('billing')->create($new_reservation, $breakdown);
            $new_invoice->status = $reservation->invoice->status;
            $new_invoice->save();

            // Copy the payments made from old reservation to new reservation
            foreach ($reservation->invoice->payments as $payment) {
                $new_payment = $payment->replicate(['invoice_id']);
                $new_payment->invoice_id = $new_invoice->id;
                $new_payment->save();
            }

            // Update balance of the invoice
            $new_invoice->balance = $new_invoice->total_amount - $new_invoice->payments->sum('amount');
            $new_invoice->save();

            // Generate PDF for the new reservation
            GenerateReservationPDF::dispatch($new_reservation);

            // Update the old reservation
            $reservation->status = ReservationStatus::RESCHEDULED->value;
            $reservation->rescheduled_to = $new_reservation->id;
            $reservation->save();

            // Update the invoice of the old reservation
            $reservation->invoice->status = InvoiceStatus::CANCELED->value;
            $reservation->invoice->save();
        });
    }

    public function processFunctionHall($data) {
        DB::transaction(function () use ($data) {
            $function_hall = FunctionHallReservations::create([
                'event_name' => $data['event_name'],
                'event_description' => $data['event_description'],
                'reservation_date' => $data['reservation_date'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
            ]);

            // Send email
            Mail::to($data['email'])->queue(new ReceivedFunctionHall($function_hall));
        });
    }
}
