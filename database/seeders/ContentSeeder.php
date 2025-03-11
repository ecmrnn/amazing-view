<?php

namespace Database\Seeders;

use App\Enums\ContentType;
use App\Models\Page;
use Database\Seeders\MilestoneSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $home_contents = collect([
            /**
             * Home page's content
             */
            [
                'view' => 'index',
                'key' => 'home_hero_image',
                'path' => 'content/home/',
                'type' => ContentType::IMAGE->value,
            ],
            [
                'view' => 'index',
                'key' => 'home_heading',
                'value' => 'Amazing View Mountain Resort',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'index',
                'key' => 'home_subheading',
                'value' => 'Where every stay becomes a story, welcome to your perfect escape!',
                'type' => ContentType::TEXT->value,
            ],
            /**
             * About page's content
             */
            [
                'view' => 'about',
                'key' => 'about_hero_image',
                'path' => 'content/about/',
                'type' => ContentType::IMAGE->value,
            ],
            [
                'view' => 'about',
                'key' => 'about_heading',
                'value' => 'Choose Amazing View Your Number One Resort!'   ,
                'type' => ContentType::TEXT->value
            ],
            [
                'view' => 'about',
                'key' => 'about_subheading',
                'value' => 'Know our story and the reasons why should you stay at our hotel.',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'about',
                'key' => 'about_history',
                'value' => 'Nestled amidst the rolling hills of Mabitac, Laguna, the Amazing View Mountain Resort beckons weary souls seeking solace in nature’s embrace. The air carries whispers of pine and adventure, urging visitors to explore its hidden trails. Thrill-seekers soar on ziplines, while warriors engage in colorful paintball battles. The rhythmic bounce of basketballs echoes across the mountainside, and courageous souls conquer obstacle courses. At the heart of the resort lies the function hall—a canvas for weddings, corporate retreats, and family reunions. And when the sun blazes, the azure swimming pool invites guests to float away their worries.',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'about',
                'key' => 'about_history_image',
                'path' => 'content/about/',
                'type' => ContentType::IMAGE->value,
            ],
            /**
             * Contact page's content
             */
            [
                'view' => 'contact',
                'key' => 'contact_hero_image',
                'path' => 'content/contact/',
                'type' => ContentType::IMAGE->value,
            ],
            [
                'view' => 'contact',
                'key' => 'contact_heading',
                'value' => 'Got any business idea? Send an Email!',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'contact',
                'key' => 'contact_subheading',
                'value' => 'You may reach us at the following phone numbers or you may opt to send an email using the given form.',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'contact',
                'key' => 'phone_number',
                'value' => '09171399334',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'contact',
                'key' => 'phone_number',
                'value' => '09051620527',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'contact',
                'key' => 'phone_number',
                'value' => '09451320863',
                'type' => ContentType::TEXT->value,
            ],
            /** 
             * Reservation page's content
            */
            [
                'view' => 'reservation',
                'key' => 'reservation_hero_image',
                'path' => 'content/reservation/',
                'type' => ContentType::IMAGE->value,
            ],
            [
                'view' => 'reservation',
                'key' => 'reservation_heading',
                'value' => 'Book a Room',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'reservation',
                'key' => 'reservation_subheading',
                'value' => 'Where every stay becomes a story, welcome to your perfect escape!',
                'type' => ContentType::TEXT->value,
            ],
            /**
             * Room page's content
            */
            [
                'view' => 'rooms',
                'key' => 'rooms_hero_image',
                'path' => 'content/rooms/',
                'type' => ContentType::IMAGE->value,
            ],
            [
                'view' => 'rooms',
                'key' => 'rooms_heading',
                'value' => 'Comfort & Elegance Amazing Experience!',
                'type' => ContentType::TEXT->value,
            ],
            [
                'view' => 'rooms',
                'key' => 'rooms_subheading',
                'value' => 'Your amazing journey awaits book now your dream getaway!',
                'type' => ContentType::TEXT->value,
            ],
        ]);

        
        foreach ($home_contents as $content) {
            $page = Page::whereView($content['view'])->first();

            Arr::forget($content, 'view');

            if ($content['type'] == 'text') {
                $page->contents()->create($content);
            } else {
                $page->mediaFiles()->create($content);
            }
        }
    }
}
