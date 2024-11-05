<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'name' => 'La Terraza',
            'description' => "La Terraza offers luxury and serenity with stunning ocean views and spacious, elegantly designed rooms. It's the perfect place to unwind and soak in the beauty of the coast.",
            'prefix' => 'LT',
            'floor_count' => 3,
            'room_row_count' => 2,
            'room_col_count' => 5,
        ]);
        
        Building::create([
            'name' => 'Cabana',
            'description' => 'Cabana provides a cozy retreat surrounded by lush gardens and natural beauty. Its tropical charm creates an idyllic escape for relaxation.',
            'prefix' => 'CB',
            'floor_count' => 2,
            'room_row_count' => 2,
            'room_col_count' => 5,
        ]);

        Building::create([
            'name' => 'Pandan Villa',
            'description' => 'Pandan Villa combines modern amenities with tranquil privacy amidst lush greenery. It offers a serene retreat from the everyday hustle.',
            'prefix' => 'PV',
            'floor_count' => 2,
            'room_row_count' => 2,
            'room_col_count' => 5,
        ]);

        Building::create([
            'name' => 'Infinity',
            'description' => 'Infinity boasts panoramic views, contemporary design, and top-tier facilities. This sophisticated building promises an elevated experience for all guests.',
            'prefix' => 'INF',
            'floor_count' => 2,
            'room_row_count' => 2,
            'room_col_count' => 5,
        ]);
    }
}
