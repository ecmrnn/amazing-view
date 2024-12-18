<?php

namespace Database\Seeders;

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
        $room = Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '101',
            'floor_number' => 1,
            'min_capacity' => 2,
            'max_capacity' => 4,
            'rate' => 2500,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        $room->amenities()->attach(1);
        $room->amenities()->attach(2);
        $room->amenities()->attach(3);
        $room->amenities()->attach(4);
        $room->amenities()->attach(5);

        Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '102',
            'floor_number' => 1,
            'min_capacity' => 4,
            'max_capacity' => 5,
            'rate' => 2700,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '103',
            'floor_number' => 1,
            'min_capacity' => 5,
            'max_capacity' => 6,
            'rate' => 3000,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '201',
            'floor_number' => 2,
            'min_capacity' => 4,
            'max_capacity' => 5,
            'rate' => 2700,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '202',
            'floor_number' => 2,
            'min_capacity' => 4,
            'max_capacity' => 5,
            'rate' => 2700,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '101',
            'floor_number' => 1,
            'min_capacity' => 5,
            'max_capacity' => 6,
            'rate' => 2500,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '102',
            'floor_number' => 1,
            'min_capacity' => 3,
            'max_capacity' => 4,
            'rate' => 2000,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '103',
            'floor_number' => 1,
            'min_capacity' => 5,
            'max_capacity' => 7,
            'rate' => 2700,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '104',
            'floor_number' => 1,
            'min_capacity' => 3,
            'max_capacity' => 4,
            'rate' => 2400,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '105',
            'floor_number' => 1,
            'min_capacity' => 5,
            'max_capacity' => 6,
            'rate' => 2800,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);
        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '106',
            'floor_number' => 1,
            'min_capacity' => 8,
            'max_capacity' => 9,
            'rate' => 4000,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);
        Room::create([
            'room_type_id' => 2,
            'building_id' => 2,
            'room_number' => '107',
            'floor_number' => 1,
            'min_capacity' => 4,
            'max_capacity' => 8,
            'rate' => 3500,
            'image_1_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);
    }
}
