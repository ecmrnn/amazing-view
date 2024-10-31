<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\content\AboutSeeder;
use Database\Seeders\content\HomeSeeder;
use Database\Seeders\content\MilestoneSeeder;
use Database\Seeders\content\RoomSeeder;
use Database\Seeders\RoomSeeder as SeedersRoomSeeder;
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
            SeedersRoomSeeder::class,
            AmenitySeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            DiscountSeeder::class,
            ReservationSeeder::class,
            ReportSeeder::class,
            // Content
            PageSeeder::class,
            AboutSeeder::class,
            HomeSeeder::class,
            MilestoneSeeder::class,
            RoomSeeder::class
        ]);
    }
}
