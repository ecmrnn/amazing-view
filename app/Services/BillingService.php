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
    public function update(Invoice $invoice, $data) {
        $invoice->update([
            'total_amount' => $data['total_amount'],
            'downpayment' => $data['downpayment'],
            'balance' => $data['balance'],
            'status' => $data['status'],
        ]);

        return $invoice;
    }

    public function breakdown(Reservation $reservation) {
        $total_amount = 0;
        $breakdown = [];

        // Compute room rates
        foreach ($reservation->rooms as $room) {
            if ($room->pivot) {
                $total_amount += $room->pivot->rate;
                $breakdown[] = [
                    'name' => $room->name,
                    'rate' => $room->pivot->rate,
                ];
            }
        }

        // Compute amenity rates
        foreach ($reservation->amenities as $amenity) {
            $total_amount += $amenity->pivot->price;
            $breakdown[] = [
                'name' => $amenity->name,
                'price' => $amenity->pivot->price,
            ];
        }

        return [
            'total_amount' => $total_amount,
            'breakdown' => $breakdown,
        ];
        
    }
}