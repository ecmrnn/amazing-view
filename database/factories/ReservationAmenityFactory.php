<?php

namespace Database\Factories;

use App\Models\Amenity;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReservationAmenity>
 */
class ReservationAmenityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory()->recycle(Reservation::all()),
            'amenity_id' => Amenity::factory()->recycle(Amenity::all()),
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}
