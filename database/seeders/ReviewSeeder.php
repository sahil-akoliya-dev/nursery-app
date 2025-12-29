<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Plant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $plants = Plant::all();
        $users = User::where('role', 'customer')->get();

        // If no customers exist, create some
        if ($users->isEmpty()) {
            $users = User::factory()->count(20)->create();
        }

        // Create reviews for products (60 reviews)
        if ($products->isNotEmpty()) {
            foreach ($products->random(min(30, $products->count())) as $product) {
                // Create 1-3 reviews per product
                $reviewCount = fake()->numberBetween(1, 3);
                
                for ($i = 0; $i < $reviewCount; $i++) {
                    Review::factory()
                        ->approved()
                        ->create([
                            'user_id' => $users->random()->id,
                            'reviewable_type' => 'App\Models\Product',
                            'reviewable_id' => $product->id,
                            'is_verified_purchase' => fake()->boolean(70), // 70% verified
                        ]);
                }
            }
        }

        // Create reviews for plants (40 reviews)
        if ($plants->isNotEmpty()) {
            foreach ($plants->random(min(20, $plants->count())) as $plant) {
                // Create 1-2 reviews per plant
                $reviewCount = fake()->numberBetween(1, 2);
                
                for ($i = 0; $i < $reviewCount; $i++) {
                    Review::factory()
                        ->approved()
                        ->create([
                            'user_id' => $users->random()->id,
                            'reviewable_type' => 'App\Models\Plant',
                            'reviewable_id' => $plant->id,
                            'is_verified_purchase' => fake()->boolean(70),
                        ]);
                }
            }
        }

        // Create some high-rated featured reviews
        Review::factory()
            ->approved()
            ->highRating()
            ->verifiedPurchase()
            ->count(10)
            ->create(function () use ($products, $plants, $users) {
                $reviewable = ($products->isNotEmpty() && fake()->boolean(50)) || $plants->isEmpty()
                    ? $products->random()
                    : $plants->random();
                
                return [
                    'user_id' => $users->random()->id,
                    'reviewable_type' => get_class($reviewable),
                    'reviewable_id' => $reviewable->id,
                    'is_featured' => true,
                    'is_verified_purchase' => true,
                ];
            });

        // Create some pending reviews (not yet approved)
        if ($products->isNotEmpty() || $plants->isNotEmpty()) {
            Review::factory()
                ->count(5)
                ->create(function () use ($products, $plants, $users) {
                    $reviewable = ($products->isNotEmpty() && fake()->boolean(50)) || $plants->isEmpty()
                        ? $products->random()
                        : $plants->random();
                    
                    return [
                        'user_id' => $users->random()->id,
                        'reviewable_type' => get_class($reviewable),
                        'reviewable_id' => $reviewable->id,
                        'is_approved' => false,
                    ];
                });
        }
    }
}

