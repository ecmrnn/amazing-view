<?php

namespace Database\Seeders\content;

use App\Models\Content;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = Content::create([
            'name' => 'rooms_heading',
            'type' => 'text',
            'value' => 'Comfort & Elegance <br> Amazing Experience!'
        ]);

        $subheading = Content::create([
            'name' => 'rooms_subheading',
            'type' => 'text',
            'value' => 'Your amazing journey awaits, <br> book now your dream getaway!'
        ]);

        $page = Page::whereTitle('Rooms')->first();
        $page->contents()->attach([
            $heading->id,
            $subheading->id,
        ]);
    }
}
