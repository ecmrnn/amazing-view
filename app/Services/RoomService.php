<?php

namespace App\Services;

use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;

class RoomService
{
    // For syncing room records on room_reservations pivot table
    // Accepets the following arguments:
    // - Reservation instance
    // - Collection of rooms to attach
    public function sync(Reservation $reservation, $rooms) {
        foreach ($reservation->rooms as $room) {
            $reservation->rooms()->detach($room->id);

            $room->status = RoomStatus::AVAILABLE->value;
            $room->save();
        }

        if (!empty($rooms)) {
            foreach ($rooms as $room) {
                $room = Room::find($room->id);

                $reservation->rooms()->attach($room->id, [
                    'rate' => $room->rate,
                ]);

                $room->status = RoomStatus::RESERVED->value;
                $room->save();
            }
        }
    }

    // For releasing rooms or marking rooms as 'Available' on the database
    // Acceps the following arguments:
    // - Reservation to access the rooms pivot table
    public function release(Reservation $reservation) {
        foreach ($reservation->rooms as $room) {
            $reservation->rooms()->detach($room->id);

            $room->status = RoomStatus::AVAILABLE->value;
            $room->save();
        }
    }
}