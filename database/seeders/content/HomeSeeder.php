<?php

namespace Database\Seeders\content;

use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = Content::create([
            'name' => 'home_heading',
            'type' => 'text',
            'value' => 'Amazing View <br> Mountain Resort'
        ]);

        $subheading = Content::create([
            'name' => 'home_subheading',
            'type' => 'text',
            'value' => 'Where every stay becomes a story, welcome to your perfect escape!'
        ]);

        FeaturedService::create([
            'image' => 'https://placehold.co/400',
            'title' => 'Outdoor Activities',
            'description' => 'Immerse yourself in nature with our exciting outdoor activities. From guided hikes to thrilling ziplining, there\'s something for every adventurer.',
        ]);

        FeaturedService::create([
            'image' => 'https://placehold.co/400',
            'title' => 'Function Hall',
            'description' => 'Our elegant function hall provides the perfect venue for weddings,  conferences, and special events. With modern amenities and picturesque  views, it’s an ideal choice for gatherings.',
        ]);

        FeaturedService::create([
            'image' => 'https://placehold.co/400',
            'title' => 'Outdoor Pool Facilities',
            'description' => 'Take a refreshing dip in our sparkling outdoor pool. Surrounded by lush  greenery, it’s a tranquil oasis for relaxation and sunbathing.',
        ]);

        $home_page = Page::whereTitle('Home')->first();
        $home_page->contents()->attach([
            $heading->id,
            $subheading->id
        ]);
    }
}
