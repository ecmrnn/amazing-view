<?php

namespace Database\Seeders;

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
            'name' => 'Corkage',
            'price' => 250,
            'quantity' => 1,
            'is_addons' => 1,
        ]);

        Amenity::create([
            'name' => 'Pet',
            'price' => 250,
            'quantity' => 1,
            'is_addons' => 1,
        ]);

        Amenity::create([
            'name' => 'Breakfast',
            'price' => 500,
            'quantity' => 1,
            'is_addons' => 1,
        ]);

        Amenity::create([
            'name' => 'Dinner',
            'price' => 500,
            'quantity' => 1,
            'is_addons' => 1,
        ]);

        Amenity::create([
            'name' => 'Electric Fan',
            'price' => 100,
            'quantity' => 20,
        ]);

        Amenity::create([
            'name' => 'Single Bed',
            'price' => 1250,
            'quantity' => 20,
        ]);

        Amenity::create([
            'name' => 'Pillows',
            'price' => 100,
            'quantity' => 20,
        ]);

        Amenity::create([
            'name' => 'Blanket',
            'price' => 150,
            'quantity' => 20,
        ]);

        Amenity::create([
            'name' => 'Crib',
            'price' => 500,
            'quantity' => 20,
        ]);

        Amenity::create([
            'name' => 'Induction Cooker',
            'price' => 1000,
            'quantity' => 20,
        ]);
    }
}
