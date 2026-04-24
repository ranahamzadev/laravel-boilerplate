<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin role
        $superAdmin = Role::create(['name' => 'super-admin']);

        // Create Admin role
        $admin = Role::create(['name' => 'admin']);

        // Create Manager role
        $manager = Role::create(['name' => 'manager']);

        // Create User role
        $user = Role::create(['name' => 'user']);

        // Assign all permissions to Super Admin
        $superAdmin->givePermissionTo(Permission::all());

        // Assign specific permissions to Admin
        $admin->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view dashboard',
            'view settings',
            'view roles',
            'view permissions',
        ]);

        // Assign limited permissions to Manager
        $manager->givePermissionTo([
            'view users',
            'edit users',
            'view dashboard',
        ]);

        // Assign basic permissions to regular User
        $user->givePermissionTo([
            'view dashboard',
        ]);

        $this->command->info('Roles seeded successfully!');
    }
}
