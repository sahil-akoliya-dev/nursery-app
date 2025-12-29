<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Indoor Plants',
                'slug' => 'indoor-plants',
                'description' => 'Beautiful plants perfect for indoor spaces. These plants thrive in low to medium light conditions and are perfect for homes and offices.',
                'image' => '/images/categories/indoor-plants.jpg',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Outdoor Plants',
                'slug' => 'outdoor-plants',
                'description' => 'Hardy plants designed for outdoor gardens, patios, and balconies. Perfect for adding greenery to your outdoor spaces.',
                'image' => '/images/categories/outdoor-plants.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Succulents',
                'slug' => 'succulents',
                'description' => 'Low-maintenance succulents that are perfect for beginners. These plants require minimal water and care.',
                'image' => '/images/categories/succulents.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Plant Care',
                'slug' => 'plant-care',
                'description' => 'Essential tools, fertilizers, and accessories to help your plants thrive. Everything you need for plant care.',
                'image' => '/images/categories/plant-care.jpg',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
            $createdCategories[$categoryData['slug']] = $category;
        }

        // Create Featured Products
        $products = [
            [
                'name' => 'Monstera Deliciosa',
                'slug' => 'monstera-deliciosa',
                'description' => 'The Monstera Deliciosa, also known as the Swiss Cheese Plant, is a stunning tropical plant with large, glossy leaves that develop unique holes as they mature. Perfect for adding a touch of the jungle to your home.',
                'short_description' => 'Stunning tropical plant with unique holey leaves. Perfect for bright, indirect light.',
                'price' => 29.99,
                'sale_price' => null,
                'stock_quantity' => 50,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'MON-DEL-001',
                'images' => ['/images/products/monstera-deliciosa.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'Snake Plant',
                'slug' => 'snake-plant',
                'description' => 'The Snake Plant (Sansevieria) is one of the most resilient houseplants. It can survive in low light conditions and requires minimal watering, making it perfect for beginners.',
                'short_description' => 'Ultra-low maintenance plant that purifies air. Perfect for any room.',
                'price' => 24.99,
                'sale_price' => null,
                'stock_quantity' => 75,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'SNA-PLA-001',
                'images' => ['/images/products/snake-plant.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'Pothos Golden',
                'slug' => 'pothos-golden',
                'description' => 'The Golden Pothos is a fast-growing, trailing vine with heart-shaped leaves variegated in green and gold. It\'s incredibly easy to care for and perfect for hanging baskets.',
                'short_description' => 'Fast-growing trailing vine with beautiful variegated leaves. Great for beginners.',
                'price' => 19.99,
                'sale_price' => 15.99,
                'stock_quantity' => 100,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'POT-GOL-001',
                'images' => ['/images/products/pothos-golden.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'Fiddle Leaf Fig',
                'slug' => 'fiddle-leaf-fig',
                'description' => 'The Fiddle Leaf Fig is a popular statement plant with large, violin-shaped leaves. It requires bright, indirect light and regular watering to thrive.',
                'short_description' => 'Stunning statement plant with large, glossy leaves. Perfect for bright rooms.',
                'price' => 49.99,
                'sale_price' => null,
                'stock_quantity' => 30,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'FID-LEA-001',
                'images' => ['/images/products/fiddle-leaf-fig.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'Peace Lily',
                'slug' => 'peace-lily',
                'description' => 'The Peace Lily is known for its elegant white flowers and air-purifying qualities. It thrives in low to medium light and prefers consistently moist soil.',
                'short_description' => 'Elegant plant with white flowers. Excellent air purifier for your home.',
                'price' => 22.99,
                'sale_price' => null,
                'stock_quantity' => 60,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'PEA-LIL-001',
                'images' => ['/images/products/peace-lily.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'ZZ Plant',
                'slug' => 'zz-plant',
                'description' => 'The ZZ Plant is nearly indestructible and perfect for low-light areas. Its glossy, dark green leaves add a modern touch to any space.',
                'short_description' => 'Nearly indestructible plant perfect for low-light areas. Very low maintenance.',
                'price' => 27.99,
                'sale_price' => null,
                'stock_quantity' => 45,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'ZZP-LAN-001',
                'images' => ['/images/products/zz-plant.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'Spider Plant',
                'slug' => 'spider-plant',
                'description' => 'The Spider Plant is a classic houseplant known for its arching leaves and ability to produce baby plantlets. It\'s very easy to care for and great for beginners.',
                'short_description' => 'Classic houseplant that produces baby plantlets. Very easy to care for.',
                'price' => 16.99,
                'sale_price' => null,
                'stock_quantity' => 80,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'SPI-PLA-001',
                'images' => ['/images/products/spider-plant.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
            [
                'name' => 'Aloe Vera',
                'slug' => 'aloe-vera',
                'description' => 'Aloe Vera is a versatile succulent known for its medicinal properties. It requires minimal water and thrives in bright, indirect sunlight.',
                'short_description' => 'Medicinal succulent perfect for sunny windows. Low maintenance.',
                'price' => 18.99,
                'sale_price' => null,
                'stock_quantity' => 90,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'ALO-VER-001',
                'images' => ['/images/products/aloe-vera.jpg'],
                'category_id' => $createdCategories['succulents']->id,
            ],
            [
                'name' => 'Rubber Plant',
                'slug' => 'rubber-plant',
                'description' => 'The Rubber Plant features large, glossy, dark green leaves that add a bold statement to any room. It prefers bright, indirect light and moderate watering.',
                'short_description' => 'Bold plant with large, glossy leaves. Perfect statement plant.',
                'price' => 34.99,
                'sale_price' => null,
                'stock_quantity' => 40,
                'in_stock' => true,
                'is_featured' => true,
                'is_active' => true,
                'sku' => 'RUB-PLA-001',
                'images' => ['/images/products/rubber-plant.jpg'],
                'category_id' => $createdCategories['indoor-plants']->id,
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }

        $this->command->info('✅ Home page data seeded successfully!');
        $this->command->info('📦 Created ' . count($categories) . ' categories');
        $this->command->info('🌱 Created ' . count($products) . ' featured products');
        $this->command->info('');
        $this->command->info('📝 Next steps:');
        $this->command->info('1. Add category images to: public/images/categories/');
        $this->command->info('2. Add product images to: public/images/products/');
        $this->command->info('3. Visit your home page to see the results!');
    }
}
