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
            'capacity' => fake()->numberBetween(1, 16),
            'image_1_path' => fake()->imageUrl(),
            'image_2_path' => fake()->imageUrl(),
            'image_3_path' => fake()->imageUrl(),
            'image_4_path' => fake()->imageUrl(),
            'status' => fake()->numberBetween(0, 3),
        ];
    }
}
