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
            'name' => 'Lepanto',
            'prefix' => 'LP',
            'floor_count' => 3,
            'room_row_count' => 2,
            'room_col_count' => 5,
        ]);
        
        Building::create([
            'name' => 'Domingo',
            'prefix' => 'DO',
            'floor_count' => 2,
            'room_row_count' => 2,
            'room_col_count' => 5,
        ]);
    }
}
