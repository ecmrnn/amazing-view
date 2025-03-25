<?php

namespace Database\Seeders;

use App\Enums\RoomStatus;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = collect([[
                'room_type_id' => 1,
                'building_id' => 1,
                'building_slot_id' => 1,
                'room_number' => '101',
                'floor_number' => 1,
                'min_capacity' => 2,
                'max_capacity' => 4,
                'rate' => 2500,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 1,
                'building_id' => 1,
                'building_slot_id' => 2,
                'room_number' => '102',
                'floor_number' => 1,
                'min_capacity' => 4,
                'max_capacity' => 5,
                'rate' => 2700,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 1,
                'building_id' => 1,
                'building_slot_id' => 3,
                'room_number' => '103',
                'floor_number' => 1,
                'min_capacity' => 5,
                'max_capacity' => 6,
                'rate' => 3000,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 1,
                'building_id' => 1,
                'building_slot_id' => 4,
                'room_number' => '201',
                'floor_number' => 2,
                'min_capacity' => 4,
                'max_capacity' => 5,
                'rate' => 2700,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 1,
                'building_id' => 1,
                'building_slot_id' => 5,
                'room_number' => '202',
                'floor_number' => 2,
                'min_capacity' => 4,
                'max_capacity' => 5,
                'rate' => 2700,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 6,
                'room_number' => '101',
                'floor_number' => 1,
                'min_capacity' => 5,
                'max_capacity' => 6,
                'rate' => 2500,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 7,
                'room_number' => '102',
                'floor_number' => 1,
                'min_capacity' => 3,
                'max_capacity' => 4,
                'rate' => 2000,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 8,
                'room_number' => '103',
                'floor_number' => 1,
                'min_capacity' => 5,
                'max_capacity' => 7,
                'rate' => 2700,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 9,
                'room_number' => '104',
                'floor_number' => 1,
                'min_capacity' => 3,
                'max_capacity' => 4,
                'rate' => 2400,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 10,
                'room_number' => '105',
                'floor_number' => 1,
                'min_capacity' => 5,
                'max_capacity' => 6,
                'rate' => 2800,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 11,
                'room_number' => '106',
                'floor_number' => 1,
                'min_capacity' => 8,
                'max_capacity' => 9,
                'rate' => 4000,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ],[
                'room_type_id' => 2,
                'building_id' => 2,
                'building_slot_id' => 12,
                'room_number' => '107',
                'floor_number' => 1,
                'min_capacity' => 4,
                'max_capacity' => 8,
                'rate' => 3500,
                'image_1_path' => '',
                'status' => RoomStatus::AVAILABLE,
            ]]
        );
        
        foreach ($rooms as $room) {
            // Find the building and the first empty slot
            $building = Building::find($room['building_id']);
            $slot = $building->slots()->whereNull('room_id')->first();

            // Assign the first empty slot to the room, then create it
            $room['building_slot_id'] = $slot->id;
            $_room = Room::create($room);

            // Assign the created room to the empty slot, then save
            $slot->room_id = $_room->id;
            $slot->save();
        }
    }
}
