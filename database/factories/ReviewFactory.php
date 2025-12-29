<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Plant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = fake()->numberBetween(1, 5);
        $isApproved = fake()->boolean(80); // 80% approved

        return [
            'user_id' => User::factory(),
            'reviewable_type' => fake()->randomElement(['App\Models\Product', 'App\Models\Plant']),
            'reviewable_id' => 1, // Will be overridden in seeders
            'rating' => $rating,
            'title' => fake()->sentence(3),
            'content' => fake()->paragraph(3),
            'images' => fake()->boolean(30) ? [
                'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=500',
            ] : null,
            'is_verified_purchase' => fake()->boolean(60), // 60% verified
            'is_featured' => fake()->boolean(10) && $rating >= 4, // Only good reviews featured
            'is_approved' => $isApproved,
            'helpful_count' => $isApproved ? fake()->numberBetween(0, 50) : 0,
            'not_helpful_count' => $isApproved ? fake()->numberBetween(0, 10) : 0,
        ];
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }

    /**
     * Indicate that the review has a high rating (4-5 stars).
     */
    public function highRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(4, 5),
        ]);
    }

    /**
     * Indicate that the review is a verified purchase.
     */
    public function verifiedPurchase(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified_purchase' => true,
        ]);
    }
}

