<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Events\ReservationCreated;
use App\Jobs\Reservation\GenerateReservationPDF;
use App\Mail\Reservation\Cancelled;
use App\Mail\Reservation\Expire;
use App\Mail\Reservation\NoShow;
use App\Mail\Reservation\Received;
use App\Mail\Reservation\ReceivedFunctionHall;
use App\Mail\Reservation\ThankYou;
use App\Mail\Reservation\Updated;
use App\Models\CancelledReservation;
use App\Models\FunctionHallReservations;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Nette\Utils\Random;

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
            $time_in = '14:00:00';
            $time_out = '12:00:00';

            if ($data['date_in'] == $data['date_out']) {
                $time_in = '08:00:00';
                $time_out = '18:00:00';
            }

            // Check if the selected rooms are available
            $reserved_rooms = Room::reservedRooms($data['date_in'], $data['date_out'])->pluck('id')->toArray();
            $selected_rooms = $data['selected_rooms'] ?? [];

            foreach ($selected_rooms as $room_id) {
                if (in_array($room_id->id, $reserved_rooms)) {
                    return null;
                }
            }

            // Assuming the $data is already validated prior to this point
            $password = Random::generate();
            
            // Check if the email corresponds to a user that is admin or receptionist
            $auth_user = User::whereEmail($data['email'] ?? '')->first();

            if (($auth_user->role ?? UserRole::GUEST->value) === UserRole::GUEST->value) {
                // Create or update the guest
                $user = User::where('email', $data['email'] ?? '')->updateOrCreate([
                    'email' => $data['email'],
                ],[
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'role' => UserRole::GUEST->value,
                    'address' => $data['address'],
                    'password' => $auth_user->password ?? $password,
                    'status' => UserStatus::ACTIVE->value
                ]);
                
                $user->assignRole('guest');
            } else {
                $user = $auth_user;
            }

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
                'promo_id' => $data['promo']->id ?? null,
                'date_in' => Arr::get($data, 'date_in'),
                'date_out' => Arr::get($data, 'date_out'),
                'time_in' => $time_in,
                'time_out' => $time_out,
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
            $breakdown['downpayment'] = $data['downpayment'] ?? 0;
    
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

            // Create the discount
            if ($reservation->senior_count > 0 || $reservation->pwd_count > 0) {
                $description = '';
                $description .= $data['senior_count'] > 0 ? 'Senior Discount ' : '';
                $description .= $data['pwd_count'] > 0 ? 'PWD Discount' : '';

                if ($data['senior_count'] > 0 && $data['pwd_count'] > 0) {
                    $description = 'Senior and PWD Discount';
                }
                
                $discounts = $reservation->discounts()->create([
                    'amount' => 0,
                    'description' => $description,
                ]);

                
                if (!empty($data['discount_attachments'])) {
                    foreach ($data['discount_attachments'] as $attachment) {
                        $file_exists = file_exists($attachment ? $attachment->getRealPath() : '');
            
                        if ($file_exists && ($data['senior_count'] > 0 || $data['pwd_count'] > 0)) {
                            $discounts->attachments()->create([
                                'image' => $attachment->store('discounts', 'public'),
                            ]);
                        }
                    }
                }
            }

            // Generate PDF
            GenerateReservationPDF::dispatch($reservation);
    
            // Broadcast the event
            broadcast(new ReservationCreated)->toOthers();

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
        $billing = new BillingService;
        $taxes = $billing->taxes($reservation->fresh());
        $payments = $reservation->invoice->payments->sum('amount');
        $waive = $reservation->invoice->waive_amount;
        
        $reservation->invoice->sub_total = $taxes['net_total'];
        $reservation->invoice->total_amount = $taxes['net_total'];
        $reservation->invoice->balance = $taxes['net_total'] - $payments;
        
        // Apply waived amount
        if ((int) $waive > 0 && $reservation->invoice->balance >= $waive) {
            $reservation->invoice->balance -=  $waive;
        }

        $reservation->invoice->save();

        // Send update email
        Mail::to($reservation->user->email)->queue(new Updated($reservation));

        // Update PDF
        GenerateReservationPDF::dispatch($reservation);

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
            $this->handlers->get('room')->release($reservation, $reservation->rooms, ReservationStatus::CANCELED->value);

            $reservation->invoice->update([
                'status' => InvoiceStatus::CANCELED,
            ]);
    
            // Send cancellation email to the guests
            Mail::to($reservation->user->email)->queue(new Cancelled($reservation));
        });
    }

    public function checkIn(Reservation $reservation) {
        DB::transaction(function () use ($reservation) {
            $reservation->status = ReservationStatus::CHECKED_IN;
            $reservation->save();

            foreach ($reservation->rooms as $room) {
                $room->status = RoomStatus::OCCUPIED;
                $room->save();

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

            $this->handlers->get('amenity')->release($reservation->fresh(), $selected_rooms);
            $this->handlers->get('room')->release($reservation->fresh(), $selected_rooms, ReservationStatus::CHECKED_OUT->value);

            // Issue invoice if not yet issued
            if (empty($reservation->invoice->issue_date)) {
                $this->handlers->get('billing')->issueInvoice($reservation->invoice);
            }

            /**
             * If all rooms are checked out: 
             * - mark reservation as 'checked out'
             * - mark invoice as 'issued'
             */
            $checked_in_rooms = $reservation->fresh()->rooms()->where('room_reservations.status', ReservationStatus::CHECKED_IN)->count();

            if (empty($checked_in_rooms)) {
                $reservation->status = ReservationStatus::CHECKED_OUT->value;
                $reservation->save();

                Mail::to($reservation->user->email)->queue(new ThankYou($reservation->fresh()));
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
        DB::transaction(function () use ($reservation, $data) {
            $reservation->status = ReservationStatus::CONFIRMED->value;
            $reservation->senior_count = $data['senior_count'] ?? $reservation->senior_count;
            $reservation->pwd_count = $data['pwd_count'] ?? $reservation->pwd_count;
            $reservation->save();

            foreach ($reservation->rooms as $room) {
                $room->pivot->status = ReservationStatus::CONFIRMED->value;
                $room->pivot->save();
            }
    
            $filename = $reservation->rid . ' - ' . strtoupper($reservation->user->last_name) . '_' . strtoupper($reservation->user->first_name) . '.pdf';
    
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

                $taxes = $this->handlers->get('billing')->taxes($reservation);
                $reservation->invoice->update([
                    'total_amount' => $taxes['net_total'],
                    'sub_total' => $taxes['sub_total'],
                    'balance' => $taxes['net_total'] - $data['amount'] ?? 0,
                    'status' => $reservation->invoice->balance > 0 ? InvoiceStatus::PARTIAL : InvoiceStatus::PAID,
                    'note' => $data['invoice_note'] ?? null,
                ]);

                if ($taxes['discount'] > 0) {
                    $reservation->discounts()->updateOrCreate(
                        ['description' => 'Senior and PWD discount'],
                        [
                            'amount' => $taxes['discount'] ?? 0,
                        ]
                    );
                }

            }

            GenerateReservationPDF::dispatch($reservation);
            return $reservation;
        });
    }

    public function expire(Reservation $reservation) {
        DB::transaction(function () use ($reservation) {
            // Update the status of the reservation
            $reservation->status = ReservationStatus::EXPIRED;
            $reservation->save();
    
            // Send email to guests with expired reservations
            Mail::to($reservation->user->email)->queue(new Expire($reservation));
    
            $this->handlers->get('room')->release($reservation, $reservation->rooms, ReservationStatus::EXPIRED->value);
            $this->handlers->get('amenity')->sync($reservation, null);
            $this->handlers->get('billing')->cancel($reservation->invoice);
    
            return $reservation;
        });
    }

    public function reactivate(Reservation $reservation) {
        $reserved_rooms = Room::reservedRooms($reservation->date_in, $reservation->date_out)->pluck('id')->toArray();
        
        if ($reservation->rooms()->whereIn('rooms.id', $reserved_rooms)->count() > 0) {
            return null;
        } 

        return DB::transaction(function () use ($reservation) {
            $reservation->status = ReservationStatus::AWAITING_PAYMENT->value;
            $reservation->expires_at = Carbon::now()->addHour();
            $reservation->save();

            $reservation->invoice->status = InvoiceStatus::PENDING;
            $reservation->invoice->save();
    
            return $reservation;
        });
    }

    public function noShow(Reservation $reservation) {
        DB::transaction(function () use ($reservation) {
            $reservation->status = ReservationStatus::NO_SHOW;
            $reservation->save();

            $reservation->invoice->status = InvoiceStatus::CANCELED;
            $reservation->invoice->save();
    
            // Send email to guests with no-show reservations
            Mail::to($reservation->user->email)->queue(new NoShow($reservation));
    
            $this->handlers->get('room')->release($reservation, $reservation->rooms, ReservationStatus::NO_SHOW->value);
            $this->handlers->get('amenity')->release($reservation, $reservation->rooms);
    
            return $reservation;
        });
    }

    public function delete(Reservation $reservation) {
        DB::transaction(function () use ($reservation) {
            $this->handlers->get('room')->sync($reservation, null);
            $this->handlers->get('amenity')->sync($reservation, null);
            $this->handlers->get('service')->sync($reservation, null);
    
            $reservation->delete();
        });
    }

    public function reschedule(Reservation $reservation, $data) {
        DB::transaction(function () use ($reservation, $data) {
            $time_in = '14:00:00';
            $time_out = '12:00:00';

            if ($data['date_in'] == $data['date_out']) {
                $time_in = '08:00:00';
                $time_out = '18:00:00';
            }

            // Copy the old reservation to a new reservation
            $new_reservation = $reservation->replicate(['rid']);
            $new_reservation->date_in = $data['date_in'];
            $new_reservation->date_out = $data['date_out'];
            $new_reservation->time_in = $time_in;
            $new_reservation->time_out = $time_out;
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
            $this->handlers->get('room')->sync($reservation, $data['selected_rooms']);

            // Update the invoice of the old reservation
            $reservation->invoice->status = InvoiceStatus::RESCHEDULED->value;
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

            $site_email = Settings::where('key', 'site_email')->pluck('value')->first();

            // Send email
            Mail::to($data['email'])->queue(new ReceivedFunctionHall($function_hall));
            Mail::to($site_email)->queue(new ReceivedFunctionHall($function_hall));
        });
    }

    public function calculateRefundAmount(Reservation $reservation) {
        $max_amount = 0;
        $refund_amount = 0;
        $date_in = $reservation->date_in;
        $date_diff = Carbon::parse(now()->format('Y-m-d'))->diffInDays($date_in);
        
        if ($reservation->invoice->payments->count() > 0) {
            foreach ($reservation->invoice->payments->pluck('amount') as $payment) {
                $max_amount += $payment;
            }
        }

        if ($max_amount > 0) {
            if ($date_diff >= 7) {
                $refund_amount = $max_amount * 1;
            } else {
                if ($date_diff > 0) {
                    $refund_amount = $max_amount * .5;
                } else {
                    $refund_amount = 0;
                }
            }
        } else {
            $refund_amount = 0;
        }

        return $refund_amount;
    }
}
