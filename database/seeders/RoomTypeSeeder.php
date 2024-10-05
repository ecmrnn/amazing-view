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
            'description' => 'Lorem ipsum dolor sit amet.',
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
        ]);

        RoomType::create([
            'name' => 'Cabana',
            'min_rate' => 2500,
            'max_rate' => 3000,
            'description' => 'Lorem ipsum dolor sit amet.',
            'image_1_path' => 'https://placehold.co/300',
            'image_2_path' => 'https://placehold.co/300',
            'image_3_path' => 'https://placehold.co/300',
            'image_4_path' => 'https://placehold.co/300',
        ]);
    }
}
