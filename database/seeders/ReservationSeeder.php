<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\RoomReservation;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservation = Reservation::create([
            'date_in' => Carbon::now()->format('Y-m-d'),
            'date_out' => Carbon::now()->addDay()->format('Y-m-d'),
            'adult_count' => 2,
            'children_count' => 0,
            'status' => Reservation::STATUS_PENDING,

            'first_name' => 'Ec',
            'last_name' => 'Maranan',
            'phone' => '09262355376',
            'address' => '410 Manila East Rd., Hulo, Pililla, Rizal',
            'email' => 'marananemanuelle@test.com',
        ]);

        $reservation->rooms()->attach(1);
    }
}
