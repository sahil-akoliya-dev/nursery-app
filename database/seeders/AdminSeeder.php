<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@nursery-app.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'phone' => '+1234567890',
        ]);
        $superAdmin->assignRole('super_admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin.user@nursery-app.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        $admin->assignRole('admin');

        // Create Manager
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@nursery-app.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);
        $manager->assignRole('manager');

        // Create Test Customer
        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1234567891',
        ]);
        $customer->assignRole('customer');

        // Create additional test customers using factory
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                $user->assignRole('customer');
            });

        // Create additional admins
        User::factory()
            ->admin()
            ->count(2)
            ->create()
            ->each(function ($user) {
                $user->assignRole('admin');
            });

        // Create managers
        User::factory()
            ->manager()
            ->count(2)
            ->create()
            ->each(function ($user) {
                $user->assignRole('manager');
            });
    }
}