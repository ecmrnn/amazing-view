<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

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
        // $date_in = fake()->dateTimeBetween(Carbon::create(2024, 1, 1), Carbon::create(2024, Carbon::now()->addMonth()->format('m'), 1));
        $date_in = Carbon::now()->format('Y-m-d');
        $date_out = Carbon::parse($date_in)->addDays(fake()->numberBetween(1, 3));
        return [
            'date_in' => $date_in,
            'date_out' => $date_out,
            'adult_count' => fake()->randomNumber(1),
            'children_count' => fake()->randomNumber(1),
            'senior_count' => 0,
            'pwd_count' => 0,
            // 'status' => fake()->numberBetween(0, 6),
            'status' => Reservation::STATUS_CONFIRMED,

            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'phone' => '09' . fake()->randomNumber(9),
            'address' => fake()->streetAddress(),
            'email' => fake()->unique()->email(),
            'note' => htmlentities(fake()->realText(50)),
        ];
    }
}
