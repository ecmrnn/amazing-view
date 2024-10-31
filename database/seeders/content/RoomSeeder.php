<?php

namespace Database\Seeders\content;

use App\Models\Content;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Content::create([
            'name' => 'rooms_heading',
            'type' => 'text',
            'value' => 'Comfort & Elegance <br> Amazing Experience!'
        ]);

        Content::create([
            'name' => 'rooms_subheading',
            'type' => 'text',
            'value' => 'Your amazing journey awaits, <br> book now your dream getaway!'
        ]);
    }
}
