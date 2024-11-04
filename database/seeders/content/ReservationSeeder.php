<?php

namespace Database\Seeders\content;

use App\Models\Content;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hero_image = Content::create([
            'name' => 'reservation_hero_image',
            'type' => 'image',
        ]);

        $heading = Content::create([
            'name' => 'reservation_heading',
            'type' => 'text',
            'value' => 'Book a Room'
        ]);

        $subheading = Content::create([
            'name' => 'reservation_subheading',
            'type' => 'text',
            'value' => 'Where every stay becomes a story, welcome to your perfect escape!'
        ]);

        $page = Page::whereTitle('Reservation')->first();
        $page->contents()->attach([
            $hero_image->id,
            $heading->id,
            $subheading->id
        ]);
    }
}
