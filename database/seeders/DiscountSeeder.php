<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::create([
            'name' => 'senior discount',
            'percentage' => 20,
            'start_date' => '2024-10-1',
            'end_date' => '2024-10-31',
            'status' => Discount::STATUS_ACTIVE
        ]);

        Discount::create([
            'name' => 'birthday discount',
            'amount' => 1000,
            'start_date' => '2024-10-1',
            'end_date' => '2024-10-31',
            'status' => Discount::STATUS_ACTIVE
        ]);

        Discount::create([
            'name' => 'holiday discount',
            'amount' => 500,
            'start_date' => '2024-10-1',
            'end_date' => '2024-10-31',
            'status' => Discount::STATUS_ACTIVE
        ]);

        Discount::create([
            'name' => 'PWD discount',
            'percentage' => 20,
            'start_date' => '2024-10-1',
            'end_date' => '2024-10-31',
            'status' => Discount::STATUS_ACTIVE
        ]);
    }
}
