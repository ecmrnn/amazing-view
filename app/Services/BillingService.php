<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Enums\PaymentPurpose;
use App\Enums\ReservationStatus;
use App\Models\Invoice;
use App\Models\Reservation;
use Carbon\Carbon;

class BillingService
{
    public function breakdown(Reservation $reservation) {
        $total = 0;
        $breakdown = [];

        // Compute room rates
        foreach ($reservation->rooms as $room) {
            if ($room->pivot) {
                $total += $room->pivot->rate;
                $breakdown[] = [
                    'name' => $room->name,
                    'rate' => $room->pivot->rate,
                ];
            }
        }

        // Compute amenity rates
        foreach ($reservation->amenities as $amenity) {
            $total += $amenity->pivot->price;
            $breakdown[] = [
                'name' => $amenity->name,
                'price' => $amenity->pivot->price,
            ];
        }

        return [
            'total' => $total,
            'breakdown' => $breakdown,
        ];
        
    }
}