<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_type_id' => RoomType::factory(),
            'building_id' => Building::factory(),
            'room_number' => fake()->numberBetween(1, 20),
            'floor_number' => fake()->numberBetween(1, 4),
            'min_capacity' => fake()->numberBetween(7, 16),
            'max_capacity' => fake()->numberBetween(8, 16),
            'rate' => fake()->randomElement([2500, 5000, 7000, 7500]),
            'image_1_path' => fake()->imageUrl(),
            'image_2_path' => fake()->imageUrl(),
            'image_3_path' => fake()->imageUrl(),
            'image_4_path' => fake()->imageUrl(),
            'status' => fake()->numberBetween(0, 3),
        ];
    }
}
