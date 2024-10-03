<?php

namespace Database\Seeders;

use App\Models\User;
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
        $guest = User::create([
            'first_name' => 'juan',
            'last_name' => 'dela cruz',
            'role' => 0,
            'status' => 0,
            'email' => 'guest@test.com',
            'password' => bcrypt('guest123'),
        ]);
        
        $frontdesk = User::create([
            'first_name' => 'ec',
            'last_name' => 'maranan',
            'role' => 1,
            'status' => 0,
            'email' => 'frontdesk@test.com',
            'password' => bcrypt('frontdesk123'),
        ]);

        $admin = User::create([
            'first_name' => 'marnie',
            'last_name' => 'maranan',
            'role' => 2,
            'status' => 0,
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $guest->assignRole('guest');
        $frontdesk->assignRole('frontdesk');
        $admin->assignRole('admin');
    }
}
