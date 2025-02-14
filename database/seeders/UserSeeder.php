<?php

namespace Database\Seeders;

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
            'phone' => '09' . $faker->randomNumber(9),
            'role' => 0,
            'status' => 0,
            'email' => 'guest@test.com',
            'password' => bcrypt('guest123'),
        ]);
        
        $frontdesk = User::create([
            'first_name' => 'ec',
            'last_name' => 'maranan',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9),
            'role' => 1,
            'status' => 0,
            'email' => 'frontdesk@test.com',
            'password' => bcrypt('frontdesk123'),
        ]);

        $admin = User::create([
            'first_name' => 'marnie',
            'last_name' => 'maranan',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9),
            'role' => 2,
            'status' => 0,
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $admin2 = User::create([
            'first_name' => 'jane',
            'last_name' => 'doe',
            'address' => $faker->address(),
            'phone' => '09' . $faker->randomNumber(9),
            'role' => 2,
            'status' => 0,
            'email' => 'admin2@test.com',
            'password' => bcrypt('admin123'),
        ]);

        $guest->assignRole('guest');
        $frontdesk->assignRole('frontdesk');
        $admin->assignRole('admin');
        $admin2->assignRole('admin');

        // $users = User::factory(20)->create();

        // foreach ($users as $user) {
        //     switch ($user->role) {
        //         case 0:
        //             $user->assignRole('guest');
        //             break;
        //         case 1:
        //             $user->assignRole('frontdesk');
        //             break;
        //         case 2:
        //             $user->assignRole('admin');
        //             break;
        //     }
        // }
    }
}
