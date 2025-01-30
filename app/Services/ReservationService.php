<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\RoomStatus;
use App\Enums\PaymentPurpose;
use App\Enums\ReservationStatus;
use App\Models\Amenity;
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
        if (isset($data['selected_rooms'])) {
            foreach ($data['selected_rooms'] as $room) {
                $reservation->rooms()->attach($room['id'], [
                    'rate' => $room['rate'],
                ]);
                $room->status = RoomStatus::RESERVED;
                $room->save();
            }
        }

        // Attach amenities to reservation
        if (isset($data['selected_amenities'])) {
            foreach ($data['selected_amenities'] as $amenity) {
                $reservation->amenities()->attach($amenity['id'], [
                    'price' => $amenity['price'],
                    'quantity' => 0,
                ]);
            }
        }

        // Attach services to reservation
        if (isset($data['selected_services'])) {
            foreach ($data['selected_services'] as $service) {
                $reservation->services()->attach($service['id'], [
                    'price' => $service['price'],
                ]);
            }
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

        $reservation->save();

        if (isset($data['selected_rooms'])) {
            $this->updateRooms($reservation, $data['selected_rooms']);
        }
        if (isset($data['selected_services'])) {
            $this->updateServices($reservation, $data['selected_services']);
        }
        if (isset($data['selected_amenities'])) {
            $this->updateAmenities($reservation, $data['selected_amenities']);
        }
        
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

    public function updateRooms(Reservation $reservation, $rooms) {
        // Detach the old and attach the new rooms to reservation
        foreach ($reservation->rooms as $room) {
            $reservation->rooms()->detach($room->id);
        }
        foreach ($rooms as $room) {
            $reservation->rooms()->attach($room->id, [
                'rate' => $room->rate,
            ]);
        }

        $reservation->save();
    }

    public function updateAmenities(Reservation $reservation, $amenities) {
        // Detach the old and attach the new amenities to reservation
        foreach ($reservation->amenities as $amenity) {
            $_amenity = Amenity::find($amenity['id']);

            $reservation->amenities()->detach($amenity['id']);

            $_amenity->quantity += (int) $amenity->pivot->quantity;
            $_amenity->save();
        }
        foreach ($amenities as $amenity) {
            $_amenity = Amenity::find($amenity['id']);

            $reservation->amenities()->attach($amenity['id'], [
                'price' => $amenity['price'],
                'quantity' => $amenity['quantity'],
            ]);

            $_amenity->quantity -= (int) $amenity['quantity'];
            $_amenity->save();
        }
    }

    public function updateServices(Reservation $reservation, $services) {
        // Detach the old and attach the new services to reservation
        foreach ($reservation->services as $service) {
            $reservation->services()->detach($service->id);
        }
        foreach ($services as $service) {
            $reservation->services()->attach($service->id, [
                'price' => $service['price'],
            ]);
        }
    }
}
