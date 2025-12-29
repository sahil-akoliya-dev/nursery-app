<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'icon' => 'sprout',
                'title' => 'Sustainability First',
                'description' => 'We source our plants from responsible growers and use eco-friendly packaging for every order.',
                'color' => 'green',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => 'droplets',
                'title' => 'Expert Care',
                'description' => 'Every plant comes with detailed care instructions, and our team is always here to help you succeed.',
                'color' => 'blue',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'icon' => 'heart',
                'title' => 'Community Driven',
                'description' => 'We\'re building a community of plant lovers. Join us for workshops, swaps, and growing together.',
                'color' => 'purple',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($features as $feature) {
            \App\Models\Feature::create($feature);
        }
    }
}
