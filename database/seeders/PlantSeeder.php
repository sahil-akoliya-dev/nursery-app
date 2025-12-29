<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indoorCategory = Category::where('slug', 'indoor-plants')->first();
        $outdoorCategory = Category::where('slug', 'outdoor-plants')->first();
        $succulentCategory = Category::where('slug', 'succulents')->first();
        $floweringCategory = Category::where('slug', 'flowering-plants')->first();
        $herbCategory = Category::where('slug', 'herbs')->first();

        $plants = [
            [
                'name' => 'Monstera Deliciosa',
                'slug' => 'monstera-deliciosa-plant',
                'scientific_name' => 'Monstera deliciosa',
                'description' => 'A climbing, flowering plant native to tropical forests of southern Mexico, south to Panama. Famous for its fenestrated leaves that resemble Swiss cheese.',
                'short_description' => 'The iconic "Swiss Cheese Plant" for tropical vibes.',
                'price' => 35.00,
                'sale_price' => null,
                'stock_quantity' => 20,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'plant_type' => 'indoor',
                'category_id' => $indoorCategory->id,
                'images' => ['https://images.unsplash.com/photo-1614594975525-e45890e2e122?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Weekly',
                    'light' => 'Bright indirect',
                    'humidity' => 'High',
                    'temperature' => '65-85°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Small to Large',
                    'growth_rate' => 'Fast',
                    'toxicity' => 'Toxic to pets',
                ],
            ],
            [
                'name' => 'Fiddle Leaf Fig',
                'slug' => 'fiddle-leaf-fig-plant',
                'scientific_name' => 'Ficus lyrata',
                'description' => 'A species of flowering plant in the mulberry and fig family Moraceae. It is native to western Africa, from Cameroon west to Sierra Leone.',
                'short_description' => 'Structural tree with large, violin-shaped leaves.',
                'price' => 55.00,
                'sale_price' => 45.00,
                'stock_quantity' => 15,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'plant_type' => 'indoor',
                'category_id' => $indoorCategory->id,
                'images' => ['https://images.unsplash.com/photo-1612435889250-5283e22032e7?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'When top inch dries',
                    'light' => 'Bright filtered',
                    'humidity' => 'Moderate',
                    'temperature' => '60-75°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Large (Tree)',
                    'growth_rate' => 'Slow',
                    'toxicity' => 'Toxic',
                ],
            ],
            [
                'name' => 'Snake Plant',
                'slug' => 'snake-plant-plant',
                'scientific_name' => 'Dracaena trifasciata',
                'description' => 'A species of flowering plant in the family Asparagaceae, native to tropical West Africa from Nigeria east to the Congo.',
                'short_description' => 'Indestructible air purifier, perfect for low light.',
                'price' => 25.00,
                'sale_price' => null,
                'stock_quantity' => 50,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'plant_type' => 'indoor',
                'category_id' => $indoorCategory->id,
                'images' => ['https://images.unsplash.com/photo-1599598425947-70e021653e5c?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Monthly',
                    'light' => 'Low to Bright',
                    'humidity' => 'Low',
                    'temperature' => '55-85°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Medium',
                    'growth_rate' => 'Medium',
                    'toxicity' => 'Toxic to pets',
                ],
            ],
            [
                'name' => 'Lavender',
                'slug' => 'lavender-plant-outdoor',
                'scientific_name' => 'Lavandula',
                'description' => 'A genus of 47 known species of flowering plants in the mint family, Lamiaceae. It is native to the Old World and is found in Cape Verde and the Canary Islands.',
                'short_description' => 'Fragrant herb beloved for its calming scent.',
                'price' => 12.00,
                'sale_price' => null,
                'stock_quantity' => 40,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'plant_type' => 'outdoor',
                'category_id' => $outdoorCategory->id,
                'images' => ['https://images.unsplash.com/photo-1499002238440-d264edd596ec?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Sparingly',
                    'light' => 'Full Sun',
                    'humidity' => 'Low',
                    'temperature' => 'Hardy',
                ],
                'plant_characteristics' => [
                    'height' => 'Small shrub',
                    'growth_rate' => 'Medium',
                    'toxicity' => 'Non-toxic',
                ],
            ],
            [
                'name' => 'Aloe Vera',
                'slug' => 'aloe-vera-plant',
                'scientific_name' => 'Aloe barbadensis miller',
                'description' => 'A succulent plant species of the genus Aloe. An evergreen perennial, it originates from the Arabian Peninsula, but grows wild in tropical, semi-tropical, and arid climates around the world.',
                'short_description' => 'Soothing succulent with medicinal gel.',
                'price' => 15.00,
                'sale_price' => null,
                'stock_quantity' => 30,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'plant_type' => 'succulent',
                'category_id' => $succulentCategory->id,
                'images' => ['https://images.unsplash.com/photo-1596547609652-9cf5d8d76921?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Bi-weekly',
                    'light' => 'Bright direct',
                    'humidity' => 'Low',
                    'temperature' => '65-80°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Small',
                    'growth_rate' => 'Slow',
                    'toxicity' => 'Toxic if eaten',
                ],
            ],
            [
                'name' => 'Peace Lily',
                'slug' => 'peace-lily-plant',
                'scientific_name' => 'Spathiphyllum',
                'description' => 'Adaptive and low-maintenance, the Peace Lily is famous for its white spoon-shaped flowers and air-purifying capabilities.',
                'short_description' => 'Elegant white blooms for low-light spaces.',
                'price' => 22.00,
                'sale_price' => null,
                'stock_quantity' => 25,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'plant_type' => 'indoor',
                'category_id' => $floweringCategory->id,
                'images' => ['https://images.unsplash.com/photo-1593482892290-f54927ae1bb6?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Keep moist',
                    'light' => 'Low to partial',
                    'humidity' => 'High',
                    'temperature' => '65-80°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Medium',
                    'growth_rate' => 'Fast',
                    'toxicity' => 'Toxic to pets',
                ],
            ],
            [
                'name' => 'Rosemary',
                'slug' => 'rosemary-plant-herb',
                'scientific_name' => 'Salvia rosmarinus',
                'description' => 'A woody, perennial herb with fragrant, evergreen, needle-like leaves and white, pink, purple, or blue flowers, native to the Mediterranean region.',
                'short_description' => 'Aromatic culinary herb.',
                'price' => 10.00,
                'sale_price' => null,
                'stock_quantity' => 60,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'plant_type' => 'indoor',
                'category_id' => $herbCategory->id,
                'images' => ['https://images.unsplash.com/photo-1545165375-ce8d5e8b9f8e?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Allow to dry',
                    'light' => 'Full sun',
                    'humidity' => 'Low',
                    'temperature' => '60-80°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Small shrub',
                    'growth_rate' => 'Medium',
                    'toxicity' => 'Non-toxic',
                ],
            ],
            [
                'name' => 'Rubber Plant',
                'slug' => 'rubber-plant',
                'scientific_name' => 'Ficus elastica',
                'description' => 'With its large, glossy, dark green leaves, the Rubber Plant is a bold statement piece. Native to southeast Asia, it can grow into a large tree in the wild but adapts well to indoor living.',
                'short_description' => 'Glossy, dark leaves for a modern look.',
                'price' => 40.00,
                'sale_price' => null,
                'stock_quantity' => 10,
                'in_stock' => true,
                'is_featured' => false,
                'is_active' => true,
                'plant_type' => 'indoor',
                'category_id' => $indoorCategory->id,
                'images' => ['https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=800&q=80'],
                'care_instructions' => [
                    'water' => 'Weekly',
                    'light' => 'Bright indirect',
                    'humidity' => 'Normal',
                    'temperature' => '60-75°F',
                ],
                'plant_characteristics' => [
                    'height' => 'Large',
                    'growth_rate' => 'Medium',
                    'toxicity' => 'Toxic to pets',
                ],
            ]
        ];

        foreach ($plants as $plantData) {
            Plant::create($plantData);
        }
    }
}
