<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feature>
 */
class FeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon' => fake()->randomElement(['sprout', 'droplets', 'heart', 'leaf', 'sun']),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'color' => fake()->randomElement(['green', 'blue', 'purple', 'orange', 'red']),
            'order' => fake()->numberBetween(1, 10),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }
}
