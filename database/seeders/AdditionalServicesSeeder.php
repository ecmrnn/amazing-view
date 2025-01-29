<?php

namespace Database\Seeders;

use App\Models\AdditionalServices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionalServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdditionalServices::create([
            'name' => 'Corkage',
            'price' => 250,
            'is_active' => true
        ]);

        AdditionalServices::create([
            'name' => 'Pet',
            'price' => 250,
            'is_active' => true
        ]);

        AdditionalServices::create([
            'name' => 'Breakfast',
            'price' => 250,
            'is_active' => true
        ]);
    }
}
