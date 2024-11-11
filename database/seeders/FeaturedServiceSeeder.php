<?php

namespace Database\Seeders;

use App\Models\FeaturedService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeaturedServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FeaturedService::create([
            'title' => 'Outdoor Activities',
            'description' => 'Immerse yourself in nature with our exciting outdoor activities. From guided hikes to thrilling ziplining, there\'s something for every adventurer.',
        ]);

        FeaturedService::create([
            'title' => 'Function Hall',
            'description' => 'Our elegant function hall provides the perfect venue for weddings,  conferences, and special events. With modern amenities and picturesque  views, it’s an ideal choice for gatherings.',
        ]);

        FeaturedService::create([
            'title' => 'Outdoor Pool Facilities',
            'description' => 'Take a refreshing dip in our sparkling outdoor pool. Surrounded by lush  greenery, it’s a tranquil oasis for relaxation and sunbathing.',
        ]);
    }
}
