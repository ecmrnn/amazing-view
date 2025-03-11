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
        $pages = collect([
            [
                'title' => 'Home',
                'url' => '/',
                'name' => 'home',
            ],
            [
                'title' => 'Rooms',
                'url' => '/rooms',
                'name' => 'rooms',
            ],
            [
                'title' => 'About',
                'url' => '/about',
                'name' => 'about',
            ],
            [
                'title' => 'Contact',
                'url' => '/contact',
                'name' => 'contact',
            ],
            [
                'title' => 'Reservation',
                'url' => '/reservation',
                'name' => 'reservation',
            ],
            [
                'title' => 'Global',
                'url' => '/global',
                'name' => 'function-hall',
            ]
        ]);

        foreach ($pages as $page) {
            Page::create([
                'title' => $page['title'],
                'url' => $page['url'],
                'name' => $page['name'],
            ]);
        }

    }
}
