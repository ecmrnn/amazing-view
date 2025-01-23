<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\RoomStatus;
use App\Enums\PaymentPurpose;
use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationService
{
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
            'date_in' => $data['date_in'],
            'date_out' => $data['date_out'],
            'senior_count' => $data['senior_count'],
            'pwd_count' => $data['pwd_count'],
            'adult_count' => $data['adult_count'],
            'children_count' => $data['children_count'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => trim(implode($data['address']), ', '),
            'note' => $data['note'],
            'expires_at' => $expires_at,
            'status' => $status,
        ]);

        // Attach the rooms to reservation
        foreach ($data['selected_rooms'] as $room) {
            $reservation->rooms()->attach($room['id'], [
                'rate' => $room['rate'],
            ]);
            $room->status = RoomStatus::RESERVED;
            $room->save();
        }

        // Attach amenities to reservation
        foreach ($data['selected_amenities'] as $amenity) {
            $reservation->amenities()->attach($amenity['id'], [
                'price' => $amenity['price'],
                'quantity' => 0,
            ]);
        }

        // Store cars for park reservation
        foreach ($data['cars'] as $car) {
            $reservation->cars()->create([
                'plate_number' => $car['plate_number'], 
                'make' => $car['make'],
                'model' => $car['model'],
                'color' => $car['color'],
            ]);
        }

        // Compute breakdown
        $billing = new BillingService();
        $breakdown = $billing->breakdown($reservation);

        // Create the invoice
        $invoice = $reservation->invoice()->create([
            'total_amount' => $breakdown['total_amount'],
            'downpayment' => 0,
            'balance' => $breakdown['total_amount'],
            'status' => Invoice::STATUS_PENDING,
        ]);

        // Create the downpayment
        if (!empty($proof_image_path)) {
            $invoice->payments()->create([
                'proof_image_path' => $proof_image_path,
                'amount' => 0,
                'purpose' => PaymentPurpose::DOWNPAYMENT,
                'payment_date' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        // Example: Notify user about the reservation
        // Notification::send($reservation->user, new ReservationCreated($reservation));
        return $reservation;
    }

    public function update(Reservation $reservation, array $data)
    {
        // Assuming the $data is already validated prior to this point
        $reservation->update([
            'date_in' => $data['date_in'],
            'date_out' => $data['date_out'],
            'adult_count' => $data['adult_count'],
            'children_count' => $data['children_count'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'note' => $data['note'],
        ]);

        // Detach the old and attach the new rooms to reservation
        foreach ($reservation->rooms as $room) {
            $reservation->rooms()->detach($room->id);
        }
        foreach ($data['selected_rooms'] as $room) {
            $reservation->rooms()->attach($room->id, [
                'rate' => $room->rate,
            ]);
            $room->status = RoomStatus::RESERVED->value;
            $room->save();
        }

        $reservation->save();

        // Detach the old and attach the new amenities to reservation

        // Update the invoice
        $billing = new BillingService();
        $breakdown = $billing->breakdown($reservation->fresh());
        $invoice_data = [
            'total_amount' => $breakdown['total_amount'],
            'downpayment' => $reservation->invoice->downpayment,
            'balance' => $breakdown['total_amount'] - $reservation->invoice->downpayment,
        ];
        $invoice_data['status'] = $invoice_data['balance'] > 0 ? InvoiceStatus::PARTIAL->value : InvoiceStatus::PAID->value;

        // Create the invoice
        $billing->update($reservation->invoice, $invoice_data);

        // Example: Notify user about the update
        // Notification::send($reservation->user, new ReservationUpdated($reservation));
        return $reservation;
    }
}
