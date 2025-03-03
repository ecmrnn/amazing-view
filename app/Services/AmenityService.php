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
        foreach ($amenities as $amenity) {
            $_amenity = Amenity::find($amenity['id']);

            // $reservation->rooms->amenities()->attach($amenity['id'], [
            //     'price' => $amenity['price'],
            //     'quantity' => $amenity['quantity'],
            // ]);

            $_amenity->quantity -= (int) $amenity['quantity'];
            $_amenity->save();
        }
    }
    
    // For syncing amenity records on reservation_amenities pivot table
    // Accepets the following arguments:
    // - Reservation instance
    // - Collection of amenities to attach
    public function sync(Reservation $reservation, $amenities) {
        dd($amenities);
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
            foreach ($amenities as $amenity) {
                $_amenity = Amenity::find($amenity['id']);
    
                // $reservation->rooms->amenities()->attach($amenity['id'], [
                //     'price' => $amenity['price'],
                //     'quantity' => $amenity['quantity'],
                // ]);

                if (in_array($reservation->status, [
                    ReservationStatus::AWAITING_PAYMENT->value,
                    ReservationStatus::PENDING->value,
                    ReservationStatus::CONFIRMED->value,
                    ReservationStatus::CHECKED_IN->value,
                ])) {
                    $_amenity->quantity -= (int) $amenity['quantity'];
                }
    
                $_amenity->save();
            }
        }

        $billing = new BillingService;
        $taxes = $billing->taxes($reservation->fresh());
        $payments = $reservation->invoice->payments->sum('amount');

        $reservation->invoice->total_amount = $taxes['net_total'];
        $reservation->invoice->balance = $taxes['net_total'] - $payments;
        $reservation->invoice->save();

    }

    // For adding amenities on edit and create reservations
    // Accepts the following arguments:
    // - Collection of amenities
    // - Amenity model instance to access both ID and current price of the amenity
    // - Quantity to be used on pivot table
    public function add($amenities, Amenity $amenity, $quantity)
    {
        if (!$amenities->contains('id', $amenity->id)) {
            $amenities->push([
                'id' => $amenity->id,
                'name' => $amenity->name,
                'quantity' => $quantity,
                'price' => $amenity->price,
            ]);

            return $amenities;
        }
        return 0;
    }

    // For removing amenities on edit and create reservations
    // Accepts the following arguments:
    // - Collection of amenities
    // - Amenity model instance
    public function remove($amenities, Amenity $amenity) {
        $amenities = $amenities->reject(function ($_amenity) use ($amenity) {
            return $_amenity['id'] == $amenity->id;
        });

        return $amenities;
    }

    // For restocking amenities on reservation check-out
    // Accepts a reservation instance
    public function release(Reservation $reservation) {
        foreach ($reservation->rooms as $room) {
            foreach ($room->amenities as $amenity) {
                $amenity->quantity += $amenity->pivot->quantity;
                $amenity->save(); 
            }
        }
    }
}