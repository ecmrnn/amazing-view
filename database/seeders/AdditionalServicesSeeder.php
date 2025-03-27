<?php

namespace Database\Seeders;

use App\Enums\ServiceStatus;
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
            'description' => 'Applies for bringing outside foods or drinks.',
            'status' => ServiceStatus::ACTIVE
        ]);

        AdditionalServices::create([
            'name' => 'Pet',
            'price' => 250,
            'description' => 'Allowed pets are limited only for dogs, cats, or any small household animals.',
            'status' => ServiceStatus::ACTIVE
        ]);
    }
}
