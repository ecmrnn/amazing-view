<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factoriess\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
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
            'min_rate' => fake()->randomElement([2500, 5000, 7500]),
            'max_rate' => fake()->randomElement([7000, 7500]),
            'description' => fake()->paragraph(5),
            'image_1_path' => fake()->imageUrl(),
            'image_2_path' => fake()->imageUrl(),
            'image_3_path' => fake()->imageUrl(),
            'image_4_path' => fake()->imageUrl(),
        ];
    }
}
