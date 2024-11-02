<?php

namespace Database\Seeders\content;

use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Page;
use Database\Seeders\FeaturedServiceSeeder;
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
            'name' => 'home_hero_image',
            'type' => 'image',
        ]);

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

        $this->call(FeaturedServiceSeeder::class);

        $page = Page::whereTitle('Home')->first();
        $page->contents()->attach([
            $heading->id,
            $subheading->id
        ]);
    }
}
