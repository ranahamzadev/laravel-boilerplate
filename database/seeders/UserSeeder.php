<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('admin');

        // Creating Admin User
        $editor = User::create([
            'name' => 'vendor',
            'email' => 'vendor@test.com',
            'password' => Hash::make('password')
        ]);
        $editor->assignRole('vendor');

        // Creating Product Manager User
        $writer = User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('password')
        ]);
        $writer->assignRole('user');
    }
}
