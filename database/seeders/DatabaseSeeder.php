<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Order matters:
     * 1. Categories (needed by products and plants)
     * 2. Admin users (needed for relationships)
     * 3. Products and Plants (needed by reviews)
     * 4. Reviews (depends on products/plants and users)
     */
    public function run(): void
    {
        $this->call([
                // Step 0: Create roles and permissions (must be first)
            RolePermissionSeeder::class,

                // Step 1: Create categories (main categories and subcategories)
            CategorySeeder::class,

                // Step 2: Create admin and test users (assigns roles)
            AdminSeeder::class,

                // Step 3: Create products (50+) and plants (30)
            ProductSeeder::class,
            PostSeeder::class,
            PlantSeeder::class,
            FeatureSeeder::class,

                // Step 4: Create reviews (100+) - depends on products/plants and users
            ReviewSeeder::class,
        ]);
    }
}