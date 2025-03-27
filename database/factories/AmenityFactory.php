<?php

namespace Database\Factories;

use App\Enums\AmenityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Amenity>
 */
class AmenityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'quantity' => fake()->numberBetween(10, 20),
            'price' => fake()->randomElement([0, 50, 100]),
            'status' => AmenityStatus::ACTIVE,
        ];
    }
}
