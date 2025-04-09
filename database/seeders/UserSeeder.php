<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guest = User::create([
            'first_name' => 'juan',
            'last_name' => 'dela cruz',
            'address' => null,
            'phone' => '09' . Random::generate(9, '0-9'),
            'role' => UserRole::GUEST,
            'status' => UserStatus::ACTIVE,
            'email' => 'guest@test.com',
            'password' => bcrypt('guest123'),
        ]);
        
        $receptionist = User::create([
            'first_name' => 'ec',
            'last_name' => 'maranan',
            'address' => null,
            'phone' => '09' . Random::generate(9, '0-9'),
            'role' => UserRole::RECEPTIONIST,
            'status' => UserStatus::ACTIVE,
            'email' => 'receptionist@test.com',
            'password' => bcrypt('receptionist123'),
        ]);

        $admin = User::create([
            'first_name' => 'marnie',
            'last_name' => 'maranan',
            'address' => null,
            'phone' => '09' . Random::generate(9, '0-9'),
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $admin2 = User::create([
            'first_name' => 'jane',
            'last_name' => 'doe',
            'address' => null,
            'phone' => '09' . Random::generate(9, '0-9'),
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
