<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'title' => 'Home',
            'url' => '/home',
        ]);

        Page::create([
            'title' => 'Rooms',
            'url' => '/rooms',
        ]);

        Page::create([
            'title' => 'About',
            'url' => '/about',
        ]);

        Page::create([
            'title' => 'Contact',
            'url' => '/contact',
        ]);

        Page::create([
            'title' => 'Reservation',
            'url' => '/reservation',
        ]);

        Page::create([
            'title' => 'Global',
            'url' => '/global',
        ]);
    }
}
