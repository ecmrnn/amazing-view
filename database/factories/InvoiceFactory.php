<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'balance' => fake()->randomElement([2500, 5000, 7500]),
            'issue_date' => fake()->date(),
            'due_date' => fake()->date(),
            'status' => fake()->numberBetween(0, 3)
        ];
    }
}
