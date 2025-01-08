<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\content\AboutSeeder;
use Database\Seeders\content\ContactSeeder;
use Database\Seeders\content\HomeSeeder;
use Database\Seeders\content\MilestoneSeeder;
use Database\Seeders\content\ReservationSeeder as ContentReservationSeeder;
use Database\Seeders\content\RoomSeeder;
use Database\Seeders\ReservationSeeder as AppReservationSeeder;
use Database\Seeders\RoomSeeder as AppRoomSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BuildingSeeder::class,
            RoomTypeSeeder::class,
            AmenitySeeder::class,
            AppRoomSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            DiscountSeeder::class,
            AppReservationSeeder::class,
            // ReportSeeder::class,
            // Content
            PageSeeder::class,
            AboutSeeder::class,
            HomeSeeder::class,
            MilestoneSeeder::class,
            RoomSeeder::class,
            ContactSeeder::class,
            ContentReservationSeeder::class,
            TestimonialSeeder::class,
        ]);
    }
}
