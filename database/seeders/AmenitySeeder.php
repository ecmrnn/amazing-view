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
            'is_addons' => 1,
        ]);

        Amenity::create([
            'name' => 'Pet',
            'price' => 250,
            'is_addons' => 1,
        ]);

        Amenity::create([
            'name' => 'Breakfast',
            'price' => 500,
            'is_addons' => 1,
        ]);

        Amenity::create([
             'name' => 'Dinner',
            'price' => 500,
            'is_addons' => 1,
        ]);
    }
}
