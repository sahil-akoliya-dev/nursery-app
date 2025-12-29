<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main Categories
        $indoor = Category::create([
            'name' => 'Indoor Plants',
            'slug' => 'indoor-plants',
            'description' => 'Perfect plants for indoor spaces that thrive in low to medium light conditions.',
            'image' => 'https://images.unsplash.com/photo-1545241047-6083a3684587?w=800&q=80',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $outdoor = Category::create([
            'name' => 'Outdoor Plants',
            'slug' => 'outdoor-plants',
            'description' => 'Beautiful plants for your garden, patio, and outdoor spaces.',
            'image' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=800&q=80',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $succulents = Category::create([
            'name' => 'Succulents',
            'slug' => 'succulents',
            'description' => 'Low-maintenance succulents perfect for beginners and busy plant parents.',
            'image' => 'https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?w=800&q=80',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $flowering = Category::create([
            'name' => 'Flowering Plants',
            'slug' => 'flowering-plants',
            'description' => 'Colorful flowering plants to brighten up any space.',
            'image' => 'https://images.unsplash.com/photo-1550859492-d5da9d8e45f3?w=800&q=80',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        $herbs = Category::create([
            'name' => 'Herbs',
            'slug' => 'herbs',
            'description' => 'Fresh herbs for cooking and culinary purposes.',
            'image' => 'https://images.unsplash.com/photo-1545165375-ce8d5e8b9f8e?w=800&q=80',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        // Tools Category
        $tools = Category::create([
            'name' => 'Garden Tools',
            'slug' => 'garden-tools',
            'description' => 'Essential tools for plant care and gardening.',
            'image' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80',
            'is_active' => true,
            'sort_order' => 6,
        ]);

        // Pots & Planters Category
        $pots = Category::create([
            'name' => 'Pots & Planters',
            'slug' => 'pots-planters',
            'description' => 'Beautiful pots and planters for your plants.',
            'image' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=800&q=80',
            'is_active' => true,
            'sort_order' => 7,
        ]);

        // Fertilizers Category
        $fertilizers = Category::create([
            'name' => 'Fertilizers',
            'slug' => 'fertilizers',
            'description' => 'Plant nutrients and fertilizers for healthy growth.',
            'image' => 'https://plus.unsplash.com/premium_photo-1679547202440-27d922aea3a8?w=800&q=80',
            'is_active' => true,
            'sort_order' => 8,
        ]);

        // Subcategories for Indoor Plants
        Category::create([
            'name' => 'Low Light Plants',
            'slug' => 'low-light-plants',
            'description' => 'Plants that thrive in low light conditions.',
            'parent_id' => $indoor->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Air Purifying Plants',
            'slug' => 'air-purifying-plants',
            'description' => 'Plants that naturally purify indoor air.',
            'parent_id' => $indoor->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Subcategories for Outdoor Plants
        Category::create([
            'name' => 'Perennials',
            'slug' => 'perennials',
            'description' => 'Plants that come back year after year.',
            'parent_id' => $outdoor->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Annuals',
            'slug' => 'annuals',
            'description' => 'Plants that complete their life cycle in one season.',
            'parent_id' => $outdoor->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Subcategories for Tools
        Category::create([
            'name' => 'Pruning Tools',
            'slug' => 'pruning-tools',
            'description' => 'Tools for pruning and trimming plants.',
            'parent_id' => $tools->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::create([
            'name' => 'Watering Tools',
            'slug' => 'watering-tools',
            'description' => 'Tools for watering and irrigation.',
            'parent_id' => $tools->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}