<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Building
        // Room Type
        // Rooms
        // Amenities

        $this->call([
            BuildingSeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            AmenitySeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
