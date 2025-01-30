<?php

namespace App\Services;

use App\Models\Amenity;
use App\Models\Reservation;

class AmenityService
{
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

    public function remove($amenities, Amenity $amenity) {
        $amenities = $amenities->reject(function ($a) use ($amenity) {
            return $a['id'] == $amenity->id;
        });

        return $amenities;
    }
}