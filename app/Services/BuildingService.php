<?php

namespace App\Services;

use App\Enums\BuildingStatus;
use App\Models\Building;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuildingService
{
    public function create($data) {
        DB::transaction(function () use ($data) {
            $data['status'] = BuildingStatus::ACTIVE;
            $data['image'] = $data['image']->store('buildings', 'public');

            $building = Building::create($data);

            // Create the slots for the building
            for ($floor=0; $floor < $building->floor_count; $floor++) { 
                for ($row = 0; $row < $building->room_row_count; $row++) { 
                    for ($col = 0; $col < $building->room_col_count; $col++) { 
                        $building->slots()->create([
                            'floor' => $floor + 1,
                            'row' => $row + 1,
                            'col' => $col + 1,
                        ]);
                    }
                }
            }
        });
    }

    public function update(Building $building, $data) {
        DB::transaction(function () use ($building, $data) {
            if ($data['image']) {
                // Delete previous image
                if ($building->image) {
                    Storage::disk('public')->delete($building->image);
                }
                
                // Store the image in the disk
                $data['image'] = $data['image']->store('buildings', 'public');
                $building->image = $data['image'];
            }
    
            // Update the building
            $building->name = $data['name'];
            $building->description = $data['description'];
            $building->save();
        });
    }

    public function delete(Building $building) {
        DB::transaction(function () use ($building) {
            if ($building->image) {
                // delete image
                Storage::disk('public')->delete($building->image);
            }
            
            $building->delete();   
        });
    }
}