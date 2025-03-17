<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $guest = User::create([
            'first_name' => 'juan',
            'last_name' => 'dela cruz',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9, true),
            'role' => UserRole::GUEST,
            'status' => UserStatus::ACTIVE,
            'email' => 'guest@test.com',
            'password' => bcrypt('guest123'),
        ]);
        
        $receptionist = User::create([
            'first_name' => 'ec',
            'last_name' => 'maranan',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9, true),
            'role' => UserRole::RECEPTIONIST,
            'status' => UserStatus::ACTIVE,
            'email' => 'receptionist@test.com',
            'password' => bcrypt('receptionist123'),
        ]);

        $admin = User::create([
            'first_name' => 'marnie',
            'last_name' => 'maranan',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9, true),
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $admin2 = User::create([
            'first_name' => 'jane',
            'last_name' => 'doe',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9, true),
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'email' => 'admin2@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $guest->assignRole('guest');
        $receptionist->assignRole('receptionist');
        $admin->assignRole('admin');
        $admin2->assignRole('admin');
    }
}
