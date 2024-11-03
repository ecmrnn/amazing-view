<?php

namespace Database\Seeders\content;

use App\Models\Content;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hero_image = Content::create([
            'name' => 'about_hero_image',
            'type' => 'image',
        ]);

        $heading = Content::create([
            'name' => 'about_heading',
            'type' => 'text',
            'value' => 'Choose Amazing View <br> Your Number One Resort!'
        ]);

        $subheading = Content::create([
            'name' => 'about_subheading',
            'type' => 'text',
            'value' => 'Know our story and the reasons why should you stay at our hotel.'
        ]);
        
        $history = Content::create([
            'name' => 'about_history',
            'type' => 'text',
            'long_value' => 'Nestled amidst the rolling hills of Mabitac, Laguna, the Amazing View Mountain Resort beckons weary souls seeking solace in nature’s embrace. The air carries whispers of pine and adventure, urging visitors to explore its hidden trails.<br><br>Thrill-seekers soar on ziplines, while warriors engage in colorful paintball battles. The rhythmic bounce of basketballs echoes across the mountainside, and courageous souls conquer obstacle courses. At the heart of the resort lies the function hall—a canvas for weddings, corporate retreats, and family reunions. And when the sun blazes, the azure swimming pool invites guests to float away their worries.'
        ]);

        $history_image = Content::create([
            'name' => 'about_history_image',
            'type' => 'image',
            'value' => null
        ]);

        $page = Page::whereTitle('About')->first();
        $page->contents()->attach([
            $hero_image->id,
            $heading->id,
            $subheading->id,
            $history->id,
            $history_image->id
        ]);
    }
}
