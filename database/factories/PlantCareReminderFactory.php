<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Plant;
use App\Models\PlantCareGuide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlantCareReminder>
 */
class PlantCareReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reminderTypes = ['watering', 'fertilizing', 'repotting', 'pruning', 'general'];
        $frequencies = ['daily', 'weekly', 'monthly', 'seasonal', 'one_time'];
        
        return [
            'user_id' => User::factory(),
            'plant_id' => Plant::factory(),
            'plant_care_guide_id' => PlantCareGuide::factory(),
            'reminder_type' => fake()->randomElement($reminderTypes),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'scheduled_date' => fake()->dateTimeBetween('now', '+30 days'),
            'frequency' => fake()->randomElement($frequencies),
            'frequency_value' => fake()->numberBetween(1, 30),
            'is_completed' => false,
            'completed_at' => null,
            'is_active' => true,
            'notification_sent' => false,
        ];
    }

    /**
     * Indicate that the reminder is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Indicate that the reminder is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the reminder is due soon.
     */
    public function dueSoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_date' => now()->addDays(2),
            'is_completed' => false,
        ]);
    }

    /**
     * Indicate that the reminder is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_date' => now()->subDays(2),
            'is_completed' => false,
        ]);
    }
}
