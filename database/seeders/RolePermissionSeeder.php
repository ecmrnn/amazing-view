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
        Permission::create(['name' => 'update reservation']);
        Permission::create(['name' => 'delete reservation']);

        Permission::create(['name' => 'create room']);
        Permission::create(['name' => 'read rooms']);
        Permission::create(['name' => 'update room']);
        Permission::create(['name' => 'delete room']);

        Permission::create(['name' => 'create room type']);
        Permission::create(['name' => 'read rooms type']);
        Permission::create(['name' => 'update room type']);
        Permission::create(['name' => 'delete room type']);

        Permission::create(['name' => 'create billing']);
        Permission::create(['name' => 'read billings']);
        Permission::create(['name' => 'update billing']);
        Permission::create(['name' => 'delete billing']);

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

        $guest_permissions = [
            'read reservations',
        ];

        $frontdesk_permissions = [
            'read guests',
            'update guest',

            'create reservation',
            'read reservations',

            'read rooms',
            'read rooms type',

            'create billing',
            'read billings',
        ];

        $admin_permissions = [
            'create guest',
            'read guests',
            'update guest',
            'delete guest',

            'create room',
            'read rooms',
            'update room',
            'delete room',

            'create room type',
            'read rooms type',
            'update room type',
            'delete room type',

            'create reservation',
            'read reservations',
            'update reservation',
            'delete reservation',

            'create billing',
            'read billings',
            'update billing',
            'delete billing',

            'create user',
            'read users',
            'update user',
            'delete user',
        ];

        // Create Roles
        $guest = Role::create(['name' => 'guest']);
        $frontdesk = Role::create(['name' => 'frontdesk']);
        $admin = Role::create(['name' => 'admin']);

        // Sync Permissions with Roles
        $guest->syncPermissions($guest_permissions);
        $frontdesk->syncPermissions($frontdesk_permissions);
        $admin->syncPermissions($admin_permissions);
    }
}
