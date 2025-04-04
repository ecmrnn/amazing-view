<?php

namespace Database\Seeders;

use App\Enums\AmenityStatus;
use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Amenity::create([
            'name' => 'Electric Fan',
            'price' => 100,
            'quantity' => 20,
            'status' => AmenityStatus::ACTIVE,
        ]);

        Amenity::create([
            'name' => 'Single Bed',
            'price' => 1250,
            'quantity' => 20,
            'status' => AmenityStatus::ACTIVE,
        ]);

        Amenity::create([
            'name' => 'Pillows',
            'price' => 100,
            'quantity' => 20,
            'status' => AmenityStatus::ACTIVE,
        ]);

        Amenity::create([
            'name' => 'Blanket',
            'price' => 150,
            'quantity' => 20,
            'status' => AmenityStatus::ACTIVE,
        ]);

        Amenity::create([
            'name' => 'Crib',
            'price' => 500,
            'quantity' => 20,
            'status' => AmenityStatus::ACTIVE,
        ]);
    }
}
