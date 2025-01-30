<?php

namespace App\Services;

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
            $reservation->amenities()->attach($amenity['id'], [
                'price' => $amenity['price'],
                'quantity' => $amenity['quantity'],
            ]);

            $amenity->quantity -= (int) $amenity['quantity'];
            $amenity->save();
        }
    }
    
    // For syncing amenity records on reservation_amenities pivot table
    // Accepets the following arguments:
    // - Reservation instance
    // - Collection of amenities to attach
    public function sync(Reservation $reservation, $amenities) {
        foreach ($reservation->amenities as $amenity) {
            $_amenity = Amenity::find($amenity['id']);
            
            $reservation->amenities()->detach($amenity['id']);
            
            $_amenity->quantity += (int) $amenity->pivot->quantity;
            $_amenity->save();
        }
        if (!empty($amenities)) {
            foreach ($amenities as $amenity) {
                $_amenity = Amenity::find($amenity['id']);
    
                $reservation->amenities()->attach($amenity['id'], [
                    'price' => $amenity['price'],
                    'quantity' => $amenity['quantity'],
                ]);
    
                $_amenity->quantity -= (int) $amenity['quantity'];
                $_amenity->save();
                logger('finished!');
            }
        }
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
}