<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::create(['name' => 'create guest']);
        Permission::create(['name' => 'read guests']);
        Permission::create(['name' => 'update guest']);
        Permission::create(['name' => 'delete guest']);

        Permission::create(['name' => 'create reservation']);
        Permission::create(['name' => 'read reservations']);
        Permission::create(['name' => 'read own reservations']);
        Permission::create(['name' => 'update reservation']);
        Permission::create(['name' => 'delete reservation']);
        Permission::create(['name' => 'cancel reservation']);
        Permission::create(['name' => 'reactivate reservation']);

        Permission::create(['name' => 'create room']);
        Permission::create(['name' => 'read rooms']);
        Permission::create(['name' => 'update room']);
        Permission::create(['name' => 'deactivate room']);

        Permission::create(['name' => 'create room type']);
        Permission::create(['name' => 'read rooms type']);
        Permission::create(['name' => 'update room type']);
        Permission::create(['name' => 'delete room type']);

        Permission::create(['name' => 'create billing']);
        Permission::create(['name' => 'read billings']);
        Permission::create(['name' => 'read own billings']);
        Permission::create(['name' => 'update billing']);
        Permission::create(['name' => 'delete billing']);

        Permission::create(['name' => 'create building']);
        Permission::create(['name' => 'read buildings']);
        Permission::create(['name' => 'update building']);
        Permission::create(['name' => 'delete building']);

        Permission::create(['name' => 'create amenity']);
        Permission::create(['name' => 'read amenities']);
        Permission::create(['name' => 'update amenity']);
        Permission::create(['name' => 'delete amenity']);

        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'read users']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'create content']);
        Permission::create(['name' => 'read contents']);
        Permission::create(['name' => 'update content']);
        Permission::create(['name' => 'delete content']);

        Permission::create(['name' => 'create report']);
        Permission::create(['name' => 'read reports']);
        Permission::create(['name' => 'update report']);
        Permission::create(['name' => 'delete report']);

        Permission::create(['name' => 'create payment']);
        Permission::create(['name' => 'read payments']);
        Permission::create(['name' => 'update payment']);
        Permission::create(['name' => 'delete payment']);

        Permission::create(['name' => 'create service']);
        Permission::create(['name' => 'read services']);
        Permission::create(['name' => 'update service']);
        Permission::create(['name' => 'delete service']);

        Permission::create(['name' => 'create announcement']);
        Permission::create(['name' => 'read announcements']);
        Permission::create(['name' => 'update announcement']);
        Permission::create(['name' => 'delete announcement']);

        $guest_permissions = [
            'read own reservations',
            'read own billings',
        ];

        $receptionist_permissions = [
            'read guests',
            'update guest',

            'create reservation',
            'read reservations',

            'read rooms',
            'read rooms type',

            'create billing',
            'read billings',
            
            'create payment',
            'read payments',
            'update payment',
        ];

        $admin_permissions = [
            'create guest',
            'read guests',
            'update guest',
            'delete guest',

            'create room',
            'read rooms',
            'update room',
            'deactivate room',

            'create room type',
            'read rooms type',
            'update room type',
            'delete room type',

            'create reservation',
            'read reservations',
            'update reservation',
            'delete reservation',
            'cancel reservation',
            'reactivate reservation',

            'create billing',
            'read billings',
            'update billing',
            'delete billing',

            'create building',
            'read buildings',
            'update building',
            'delete building',

            'create amenity',
            'read amenities',
            'update amenity',
            'delete amenity',

            'create user',
            'read users',
            'update user',
            'delete user',

            'create report',
            'read reports',
            'update report',
            'delete report',

            'create payment',
            'read payments',
            'update payment',
            'delete payment',

            'create service',
            'read services',
            'update service',
            'delete service',

            'create announcement',
            'read announcements',
            'update announcement',
            'delete announcement',
        ];

        // Create Roles
        $guest = Role::create(['name' => 'guest']);
        $receptionist = Role::create(['name' => 'receptionist']);
        $admin = Role::create(['name' => 'admin']);

        // Sync Permissions with Roles
        $guest->syncPermissions($guest_permissions);
        $receptionist->syncPermissions($receptionist_permissions);
        $admin->syncPermissions($admin_permissions);
    }
}
