<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class RoomService
{
    // For attaching selected rooms on the reservation, to be stored on room_reservations pivot table
    // Accepts the following arguments:
    // - Reservation instance
    // - Rooms to attach
    public function attach(Reservation $reservation, $rooms) {
        foreach ($rooms as $room) {
            $reservation->rooms()->attach($room->id, [
                'rate' => $room->rate,
                'status' => $reservation->status,
            ]);
            
            $room->status = RoomStatus::RESERVED;
            $room->save();
        }
    }
    // For syncing room records on room_reservations pivot table
    // Accepets the following arguments:
    // - Reservation instance
    // - Collection of rooms to attach
    public function sync(Reservation $reservation, $rooms) {
        if ($reservation->rooms->count() > 0) {
            foreach ($reservation->rooms as $room) {
                $reservation->rooms()->detach($room->id);
    
                $room->status = RoomStatus::AVAILABLE->value;
                $room->save();

                $reservation->invoice->balance -= $room->rate;
                $reservation->save();
            }
        }

        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $room = Room::find($room->id);

                $reservation->rooms()->attach($room->id, [
                    'rate' => $room->rate,
                    'status' => $reservation->status,
                ]);

                $room->status = RoomStatus::RESERVED->value;
                $room->save();

                $reservation->invoice->balance += $room->rate;
                $reservation->invoice->save();
            }
        }
    }

    // For releasing rooms or marking rooms as 'Available' on the database
    // Acceps the following arguments:
    // - Reservation to access the rooms pivot table
    public function release(Reservation $reservation, $selected_rooms) {
        $rooms = $reservation->rooms->whereIn('id', $selected_rooms->pluck('id'));
        
        foreach ($rooms as $room) {
            $room->pivot->status = ReservationStatus::CANCELED->value;
            $room->pivot->save();
            
            $room->status = RoomStatus::AVAILABLE->value;
            $room->save();
        }
    }

    public function changeStatus(Room $room) {
        DB::transaction(function () use ($room) {
            if ($room->status == RoomStatus::AVAILABLE->value) {
                return $room->update([
                    'status' => RoomStatus::UNAVAILABLE,
                ]);
            }

            return $room->update([
                'status' => RoomStatus::AVAILABLE,
            ]);
        });
    }
}