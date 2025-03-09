<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Models\Amenity;
use App\Models\Reservation;

class AmenityService
{
    // For attaching selected amenities on the reservation, to be stored on reservation_amenities pivot table
    // Accepts the following arguments:
    // - Reservation instance
    // - Amenities to attach
    public function attach(Reservation $reservation, $amenities) {
        foreach ($reservation->rooms as $room) {
            foreach ($amenities as $amenity) {
                if ($amenity['room_number'] == $room->building->prefix . ' ' . $room->room_number) {
                    $_amenity = Amenity::find($amenity['id']);
        
                    $room->amenities()->attach($amenity['id'], [
                        'reservation_id' => $reservation->id,
                        'price' => $amenity['price'],
                        'quantity' => $amenity['quantity'],
                    ]);

                    if (in_array($reservation->status, [
                        ReservationStatus::AWAITING_PAYMENT->value,
                        ReservationStatus::PENDING->value,
                        ReservationStatus::CONFIRMED->value,
                        ReservationStatus::CHECKED_IN->value,
                    ])) {
                        $_amenity->quantity -= (int) $amenity['quantity'];
                        $_amenity->save();
                    }
                }
            }
        }
    }
    
    // For syncing amenity records on reservation_amenities pivot table
    // Accepets the following arguments:
    // - Reservation instance
    // - Collection of amenities to attach
    public function sync(Reservation $reservation, $amenities) {
        foreach ($reservation->rooms as $room) {
            foreach ($room->amenities as $amenity) {
                $_amenity = Amenity::find($amenity['id']);
                
                $room->amenities()->detach($amenity['id']);
                
                if (in_array($reservation->status, [
                    ReservationStatus::AWAITING_PAYMENT->value,
                    ReservationStatus::PENDING->value,
                    ReservationStatus::CONFIRMED->value,
                    ReservationStatus::CHECKED_IN->value,
                ])) {
                    $_amenity->quantity += (int) $amenity->pivot->quantity;
                }
                $_amenity->save();
            }
        }
        if (!empty($amenities)) {
            $this->attach($reservation, $amenities);
        }

        $billing = new BillingService;
        $taxes = $billing->taxes($reservation->fresh());
        $payments = $reservation->invoice->payments->sum('amount');

        $reservation->invoice->total_amount = $taxes['net_total'];
        $reservation->invoice->balance = $taxes['net_total'] - $payments;
        $reservation->invoice->save();

    }

    // For adding amenities on edit and create reservations
    public function add(Reservation $reservation, Amenity $amenity, $amenities, $quantity, $room_number)
    {
        $amenities->push([
            'id' => $amenity->id,
            'room_number' => $room_number,
            'name' => $amenity->name,
            'quantity' => $quantity,
            'price' => $amenity->price,
            'max' => $amenity->quantity - $quantity,
        ]);

        return $amenities;
    }

    // For removing amenities on edit and create reservations
    // Accepts the following arguments:
    // - Collection of amenities
    // - Amenity model instance
    public function remove(Amenity $amenity, $amenities, $room_number) {
        $amenities = $amenities->reject(function ($_amenity) use ($amenity, $room_number) {
            return $_amenity['room_number'] == $room_number && $_amenity['id'] == $amenity->id;
        });

        return $amenities;
    }

    // For restocking amenities on reservation check-out
    // Accepts a reservation instance
    public function release(Reservation $reservation) {
        foreach ($reservation->rooms as $room) {
            if ($room->pivot->status == ReservationStatus::CHECKED_OUT->value) {
                foreach ($room->amenities as $amenity) {
                    $amenity->quantity += $amenity->pivot->quantity;
                    $amenity->save(); 
                }
            }
        }
    }
}