<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date_in' => fake()->date(),
            'date_out' => fake()->date(),
            'adult_count' => fake()->numberBetween(1, 6),
            'children_count' => fake()->numberBetween(1, 6),
            'status' => fake()->numberBetween(0, 3),
        ];
    }
}
