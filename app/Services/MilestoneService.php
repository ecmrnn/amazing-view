<?php 

namespace App\Services;

use App\Enums\MilestoneStatus;
use App\Models\Milestone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MilestoneService
{
    public function create($data) {
        DB::transaction(function () use ($data) {
            $image_filename = $data['image']->store('milestones', 'public');

            Milestone::create([
                'milestone_image' => $image_filename,
                'title' => $data['title'],
                'description' => $data['description'],
                'date_achieved' => $data['date_achieved']
            ]);
        });
    }

    public function edit(Milestone $milestone, $data) {
        DB::transaction(function () use ($milestone, $data) {
            if (!empty($data['image'])) {
                // delete saved image
                if (!empty($milestone->milestone_image)) {
                    Storage::disk('public')->delete($milestone->milestone_image);
                }
                
                $milestone->milestone_image = $data['image']->store('milestones', 'public');
            }
            
            $milestone->title = $data['title'];
            $milestone->description = $data['description'];    
            $milestone->date_achieved = $data['date_achieved'];
            $milestone->save();
        });
    }

    public function delete(Milestone $milestone) {
        DB::transaction(function () use ($milestone) {
            Storage::disk('public')->delete($milestone->milestone_image);
            $milestone->delete();
        });
    }

    public function toggleStatus(Milestone $milestone) {
        DB::transaction(function () use ($milestone){
            if ($milestone->status == MilestoneStatus::ACTIVE->value) {
                return $milestone->update([
                    'status' => MilestoneStatus::INACTIVE
                ]);
            }

            return $milestone->update([
                'status' => MilestoneStatus::ACTIVE
            ]);
        });
    }
}
