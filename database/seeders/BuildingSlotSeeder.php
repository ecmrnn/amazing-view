<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::all();

        foreach ($buildings as $building) {
            for ($floor=0; $floor < $building->floor_count; $floor++) { 
                for ($row = 0; $row < $building->room_row_count; $row++) { 
                    for ($col = 0; $col < $building->room_col_count; $col++) { 
                        $building->slots()->create([
                            'floor' => $floor + 1,
                            'row' => $row + 1,
                            'col' => $col + 1,
                            'room_id' => null,
                        ]);
                    }
                }
            }
        }
    }
}
