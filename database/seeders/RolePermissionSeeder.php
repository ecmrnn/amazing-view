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
        Permission::create(['name' => 'create room']);
        Permission::create(['name' => 'read rooms']);
        Permission::create(['name' => 'update room']);
        Permission::create(['name' => 'delete room']);

        $frontdesk_permissions = [
            'read rooms',
            'update room'
        ];

        $admin_permission = [
            'create room',
            'read rooms',
            'update room',
            'delete room'
        ];

        // Create Roles
        $guest = Role::create(['name' => 'guest']);
        $frontdesk = Role::create(['name' => 'frontdesk']);
        $admin = Role::create(['name' => 'admin']);

        // Sync Permissions with Roles
        $frontdesk->syncPermissions($frontdesk_permissions);
    }
}
