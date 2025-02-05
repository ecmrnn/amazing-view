<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentPurpose;
use App\Enums\ReservationStatus;
use App\Models\CancelledReservation;
use App\Models\Invoice;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Arr;

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
        // Assuming the $data is already validated prior to this point
        $expires_at = Carbon::now()->addHour();
        $status = ReservationStatus::AWAITING_PAYMENT;

        // Store proof of payment to payments folder
        if (!empty($data['proof_image_path'])) {
            $proof_image_path = $data['proof_image_path']->store('payments', 'public');
            $expires_at = null;
            $status = ReservationStatus::PENDING;   
        }

        // Create the reservation
        $reservation = Reservation::create([
            'date_in' => Arr::get($data, 'date_in'),
            'date_out' => Arr::get($data, 'date_out'),
            'senior_count' => Arr::get($data, 'senior_count'),
            'pwd_count' => Arr::get($data, 'pwd_count'),
            'adult_count' => Arr::get($data, 'adult_count'),
            'children_count' => Arr::get($data, 'children_count'),
            'first_name' => Arr::get($data, 'first_name'),
            'last_name' => Arr::get($data, 'last_name'),
            'email' => Arr::get($data, 'email'),
            'phone' => Arr::get($data, 'phone'),
            'address' => trim(implode(', ', Arr::get($data, 'address'))),
            'note' => Arr::get($data, 'note'),
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

        // Create the invoice
        $invoice = $reservation->invoice()->create([
            'total_amount' => $breakdown['sub_total'],
            'downpayment' => 0,
            'balance' => $breakdown['sub_total'],
            'status' => Invoice::STATUS_PENDING,
        ]);

        // Create the downpayment
        if (!empty($proof_image_path)) {
            $invoice->payments()->create([
                'proof_image_path' => $proof_image_path,
                'amount' => 0,
                'purpose' => PaymentPurpose::DOWNPAYMENT,
                'payment_date' => now(),
            ]);
        }

        // Example: Notify user about the reservation
        // Notification::send($reservation->user, new ReservationCreated($reservation));
        return $reservation;
    }

    public function update(Reservation $reservation, $data)
    {
        // Assuming the $data is already validated prior to this point
        $reservation->update([
            'date_in' => Arr::get($data, 'date_in'),
            'date_out' => Arr::get($data, 'date_out'),
            'resched_date_in' => Arr::get($data, 'resched_date_in', $reservation->resched_date_in),
            'resched_date_out' => Arr::get($data, 'resched_date_out', $reservation->resched_date_out),
            'adult_count' => Arr::get($data, 'adult_count'),
            'children_count' => Arr::get($data, 'children_count'),
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

        // Example: Notify user about the update
        // Notification::send($reservation->user, new ReservationUpdated($reservation));
        return $reservation;
    }

    public function cancel(Reservation $reservation, $data) {
        $reservation->canceled_at = now();
        $reservation->status = ReservationStatus::CANCELED;
        $reservation->save();

        CancelledReservation::create([
            'reservation_id' => $reservation->id,
            'reason' => $data['reason'],
            'canceled_by' => $data['canceled_by'],
            'canceled_at' => now(),
        ]);

        // Update amenities and rooms pivot tables
        $this->handlers->get('amenity')->sync($reservation, null);
        $this->handlers->get('room')->sync($reservation, null);
    }
}
