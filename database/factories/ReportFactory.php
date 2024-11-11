<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::whereId(3)->first();
        return [
            'user_id' => $user->id,
            'name' => fake()->word(),
            'description' => fake()->sentence(6),
            'type' => fake()->randomElement(['reservation summary', 'daily reservations', 'occupancy report', 'financial report']),
            'format' => fake()->randomElement(['excel', 'pdf']),
            'note' => fake()->sentence(10),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(),
        ];
    }
}
