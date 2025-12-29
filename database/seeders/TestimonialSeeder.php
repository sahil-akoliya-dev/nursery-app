<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah J.',
                'role' => 'Plant Mom',
                'content' => 'The plants I received were in perfect condition! The packaging was eco-friendly and secure. Highly recommend!',
                'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80',
                'rating' => 5,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Michael C.',
                'role' => 'Interior Designer',
                'content' => 'Nursery App has the best selection of rare plants. My clients love the unique varieties I find here.',
                'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&q=80',
                'rating' => 5,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Emily R.',
                'role' => 'New Gardener',
                'content' => 'I was nervous about ordering plants online, but the care guides made it so easy. My Monstera is thriving!',
                'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&q=80',
                'rating' => 4,
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            \App\Models\Testimonial::create($testimonial);
        }
    }
}
