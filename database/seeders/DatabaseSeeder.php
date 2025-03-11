<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\MilestoneSeeder;
use Database\Seeders\ReservationSeeder as ReservationSeeder;
use Database\Seeders\RoomSeeder as RoomSeeder;
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
            RoomSeeder::class,
            AdditionalServicesSeeder::class,
            AmenitySeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            // DiscountSeeder::class,
            // ReservationSeeder::class,
            // ReportSeeder::class,
            
            // Content
            PageSeeder::class,
            ContentSeeder::class,
            FeaturedServiceSeeder::class,
            MilestoneSeeder::class,
        ]);
    }
}
