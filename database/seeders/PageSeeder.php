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
                'view' => 'index',
            ],
            [
                'title' => 'Rooms',
                'url' => '/rooms',
                'view' => 'rooms',
            ],
            [
                'title' => 'About',
                'url' => '/about',
                'view' => 'about',
            ],
            [
                'title' => 'Contact',
                'url' => '/contact',
                'view' => 'contact',
            ],
            [
                'title' => 'Room Reservation',
                'url' => '/reservation',
                'view' => 'reservation',
            ],
            [
                'title' => 'Function Hall Reservation',
                'url' => '/function-hall',
                'view' => 'function-hall',
            ],
            [
                'title' => 'Find Reservation',
                'url' => '/search',
                'view' => 'search',
            ],
            [
                'title' => 'Global',
                'url' => '/global',
                'view' => 'global',
            ],
        ]);

        foreach ($pages as $page) {
            Page::create([
                'title' => $page['title'],
                'url' => $page['url'],
                'view' => $page['view'],
            ]);
        }

    }
}
