<?php

namespace App\Services;

use App\Enums\AmenityStatus;
use App\Enums\InvoiceStatus;
use App\Enums\ReservationStatus;
use App\Models\Amenity;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class AmenityService
{
    public function create($data) {
        return DB::transaction(function () use ($data) {
            return Amenity::create($data);
        });
    }

    public function update(Amenity $amenity, $data) {
        return DB::transaction(function () use ($amenity, $data) {
            return $amenity->update([
                'name' => $data['name'],
                'quantity' => $data['quantity'],
                'price' => $data['price'],
            ]);
        });
    }

    public function toggleStatus(Amenity $amenity) {
        return DB::transaction(function () use ($amenity) {
            if ($amenity->status == AmenityStatus::ACTIVE->value) {
                return $amenity->update([
                    'status' => AmenityStatus::INACTIVE
                ]);
            } else {
                return $amenity->update([
                    'status' => AmenityStatus::ACTIVE
                ]);
            }
        });
    }

    // For attaching selected amenities on the reservation, to be stored on reservation_amenities pivot table
    // Accepts the following arguments:
    // - Reservation instance
    // - Amenities to attach
    public function attach(Reservation $reservation, $amenities) {
        foreach ($reservation->rooms as $room) {
            foreach ($amenities as $amenity) {
                if ($amenity['room_number'] == $room->room_number) {
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
        DB::transaction(function () use ($reservation, $amenities) {
            foreach ($reservation->rooms as $room) {
                foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity) {
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
            $waive = $reservation->invoice->waive_amount;
            
            $reservation->invoice->sub_total = $taxes['net_total'];
            $reservation->invoice->total_amount = $taxes['net_total'];
            $reservation->invoice->balance = $taxes['net_total'] - $payments;

            // Apply waived amount
            if ((int) $waive > 0 && $reservation->invoice->balance >= $waive) {
                $reservation->invoice->balance -=  $waive;
            }

            if ($reservation->invoice->balance > 0) {
                $reservation->invoice->status = InvoiceStatus::PARTIAL->value;
            } else {
                $reservation->invoice->status = InvoiceStatus::PAID->value;
            }
            
            $reservation->invoice->save();
        });
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
    public function release(Reservation $reservation, $selected_rooms) {
        $rooms = $reservation->rooms->whereIn('id', $selected_rooms->pluck('id'));

        foreach ($rooms as $room) {
            foreach ($room->amenitiesForReservation($reservation->id)->get() as $amenity) {
                $amenity->quantity += $amenity->pivot->quantity;
                $amenity->save(); 
            }
        }
    }
}