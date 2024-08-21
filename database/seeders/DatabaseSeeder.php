<?php

namespace Database\Seeders;

use App\Models\InvoicePayment;
use App\Models\ReservationAmenity;
use App\Models\RoomAmenity;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        InvoicePayment::factory(10)->create();
        ReservationAmenity::factory(10)->create();
        RoomAmenity::factory(10)->create();
    }
}
