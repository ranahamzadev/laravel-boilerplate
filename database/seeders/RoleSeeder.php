<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        $vendor = Role::create(['name' => 'vendor']);
        $user = Role::create(['name' => 'user']);

        $vendor->givePermissionTo([
            'view-profile',
            'view-dashboard',
        ]);
        $user->givePermissionTo([
            'view-profile',
            'view-dashboard',
        ]);
    }
}
