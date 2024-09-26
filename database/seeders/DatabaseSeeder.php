<?php

namespace Database\Seeders;

use App\Models\InvoicePayment;
use App\Models\ReservationAmenity;
use App\Models\RoomAmenity;
use App\Models\RoomReservation;
use App\Models\RoomType;
use Database\Factories\BuildingFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('buildings')->insert([
            'name' => 'Lepanto',
            'floor_count' => 2
        ]);

        DB::table('room_types')->insert([
            'name' => 'La Terraza',
            'min_rate' => 2500,
            'max_rate' => 3000,
            'description' => 'Lorem ipsum dolor sit amet.',
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
        ]);

        DB::table('room_types')->insert([
            'name' => 'Cabana',
            'min_rate' => 2500,
            'max_rate' => 3000,
            'description' => 'Lorem ipsum dolor sit amet.',
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
        ]);

        DB::table('rooms')->insert([
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

        DB::table('rooms')->insert([
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

        DB::table('rooms')->insert([
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

        DB::table('rooms')->insert([
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
        DB::table('rooms')->insert([
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

        DB::table('amenities')->insert([
            'name' => 'Corkage',
            'quantity' => 0,
            'price' => 250,
            'is_reservable' => 1,
        ]);
        DB::table('amenities')->insert([
            'name' => 'Pet',
            'quantity' => 0,
            'price' => 250,
            'is_reservable' => 1,
        ]);
        DB::table('amenities')->insert([
            'name' => 'Breakfast',
            'quantity' => 0,
            'price' => 500,
            'is_reservable' => 1,
        ]);
        DB::table('amenities')->insert([
            'name' => 'Dinner',
            'quantity' => 0,
            'price' => 500,
            'is_reservable' => 1,
        ]);
    }
}
