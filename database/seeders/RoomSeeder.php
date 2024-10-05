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
        Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '101',
            'floor_number' => 1,
            'min_capacity' => 2,
            'max_capacity' => 4,
            'rate' => 2500,
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 1,
            'building_id' => 1,
            'room_number' => '102',
            'floor_number' => 1,
            'min_capacity' => 4,
            'max_capacity' => 5,
            'rate' => 2700,
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
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
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 1,
            'room_number' => '101',
            'floor_number' => 1,
            'min_capacity' => 5,
            'max_capacity' => 6,
            'rate' => 2500,
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);

        Room::create([
            'room_type_id' => 2,
            'building_id' => 1,
            'room_number' => '102',
            'floor_number' => 1,
            'min_capacity' => 3,
            'max_capacity' => 4,
            'rate' => 2000,
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
            'status' => 0,
        ]);
    }
}
