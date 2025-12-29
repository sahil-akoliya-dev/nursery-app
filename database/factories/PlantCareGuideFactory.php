<?php

namespace Database\Factories;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlantCareGuide>
 */
class PlantCareGuideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $careLevels = ['beginner', 'intermediate', 'expert'];
        $lightRequirements = ['Full Sun', 'Partial Sun', 'Shade', 'Indirect Light'];
        $waterNeeds = ['Low', 'Moderate', 'High'];
        $soilTypes = ['Well-draining', 'Sandy', 'Loamy', 'Clay', 'Potting Mix'];
        
        return [
            'plant_id' => Plant::factory(),
            'plant_type' => fake()->randomElement(['indoor', 'outdoor', 'succulent', 'herb']),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(5),
            'care_level' => fake()->randomElement($careLevels),
            'light_requirements' => fake()->randomElement($lightRequirements),
            'water_needs' => fake()->randomElement($waterNeeds),
            'humidity_requirements' => fake()->numberBetween(30, 80) . '%',
            'temperature_range' => [
                'min' => fake()->numberBetween(50, 65),
                'max' => fake()->numberBetween(70, 85),
            ],
            'soil_type' => fake()->randomElement($soilTypes),
            'fertilizer_schedule' => fake()->randomElement(['Monthly', 'Bi-weekly', 'Seasonal', 'As needed']),
            'repotting_frequency' => fake()->randomElement(['Annually', 'Every 2 years', 'As needed']),
            'pruning_instructions' => fake()->paragraph(3),
            'common_problems' => [
                fake()->sentence(),
                fake()->sentence(),
            ],
            'seasonal_care' => [
                'spring' => fake()->paragraph(2),
                'summer' => fake()->paragraph(2),
                'fall' => fake()->paragraph(2),
                'winter' => fake()->paragraph(2),
            ],
            'is_active' => true,
            'created_by' => null,
        ];
    }

    /**
     * Indicate that the guide is for beginners.
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'care_level' => 'beginner',
        ]);
    }

    /**
     * Indicate that the guide is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
