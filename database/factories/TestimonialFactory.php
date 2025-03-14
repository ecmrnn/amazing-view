<?php

namespace Database\Factories;

use App\Enums\TestimonialStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'testimonial' => fake()->realText(fake()->numberBetween(100, 200)),
            'rating' => fake()->numberBetween(3, 5),
            'status' => TestimonialStatus::ACTIVE,
        ];
    }
}
