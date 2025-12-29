<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->jobTitle(),
            'content' => fake()->paragraph(3),
            'image' => fake()->imageUrl(100, 100, 'people'),
            'rating' => fake()->numberBetween(3, 5), // Most testimonials are positive
            'order' => fake()->numberBetween(1, 10),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }
}
