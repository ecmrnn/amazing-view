<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomType::create([
            'name' => 'La Terraza',
            'min_rate' => 2500,
            'max_rate' => 3000,
            'description' => 'Discover the epitome of elegance in our La Terreza rooms, where breathtaking mountain views meet luxurious comfort. Relax and unwind in a serene setting that perfectly blends sophistication with tranquility.',
        ]);

        RoomType::create([
            'name' => 'Cabana',
            'min_rate' => 2500,
            'max_rate' => 3000,
            'description' => 'Immerse yourself in tropical bliss with our Cabana rooms, where comfort meets island charm. Enjoy your private retreat, surrounded by lush gardens and the soothing sounds of nature.',
        ]);

        RoomType::create([
            'name' => 'Pandan Villa',
            'min_rate' => 2500,
            'max_rate' => 3000,
            'description' => 'Unwind in our Pandan Villa, where luxurious comfort meets tranquil privacy amidst lush greenery. This serene retreat offers an escape from the everyday, complete with modern amenities and breathtaking views.',
        ]);
    }
}
