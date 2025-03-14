<?php

namespace Database\Seeders;

use App\Models\Milestone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MilestoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Milestone::create([
            'milestone_image' => '',
            'title' => 'Cleanest and Best Resort Class A 1st Place',
            'description' => 'Recognized as one of the cleanest and best resorts by the Provincial Government of Laguna, Laguna Tourist, Culture, Arts and Trade Office.',
            'date_achieved' => Carbon::now()->format('Y-m-d')
        ]);
        Milestone::create([
            'milestone_image' => '',
            'title' => 'Cleanest and Best Resort Class A 2nd Place',
            'description' => 'Recognized as one of the cleanest and best resorts by the Provincial Government of Laguna, Laguna Tourist, Culture, Arts and Trade Office.',
            'date_achieved' => Carbon::now()->format('Y-m-d')
        ]);
        Milestone::create([
            'milestone_image' => '',
            'title' => 'Cleanest and Best Resort Class A 3rd Place',
            'description' => 'Recognized as one of the cleanest and best resorts by the Provincial Government of Laguna, Laguna Tourist, Culture, Arts and Trade Office.',
            'date_achieved' => Carbon::now()->format('Y-m-d')
        ]);
    }
}
