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
                'title' => 'Reservation',
                'url' => '/reservation',
                'view' => 'reservation',
            ],
            [
                'title' => 'Function Hall',
                'url' => '/function-hall',
                'view' => 'function-hall',
            ]
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
