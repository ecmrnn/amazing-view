<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Testimonial::create([
            'name' => 'Majo Lajarca Montemayor',
            'rating' => 5,
            'testimonial' => 'Great view, friendly and accomodating staff, clean and safe. Facility is awesome, wonderful place to relax and unwind. Close to nature.',
            'status' => 0
        ]);

        Testimonial::create([
            'name' => 'Noemi M. Lapus',
            'rating' => 5,
            'testimonial' => 'The name speaks for itself. Truly AMAZING. Close to nature. We just had a short stay with my college friends (overnight) but we really enjoy the place. Perfect for family outings, team buildings, events etc. Good for relaxation and soul searching.',
            'status' => 0
        ]);

        Testimonial::create([
            'name' => 'Ara Embile',
            'rating' => 5,
            'testimonial' => 'I was impressed with how this resort is giving justice to its name - the staffs are friendly and approachable, the facilities are clean, and the ambiance in general is very relaxing!',
            'status' => 0
        ]);

        Testimonial::factory(6)->create();
    }
}
