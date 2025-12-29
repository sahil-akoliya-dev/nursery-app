<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Premium Potting Soil', 'Garden Shears', 'Watering Can', 'Plant Fertilizer',
            'Seed Starter Kit', 'Garden Gloves', 'Plant Markers', 'Pruning Snips',
            'Soil pH Tester', 'Garden Tool Set', 'Compost Bin', 'Plant Stand',
            'Grow Lights', 'Humidity Tray', 'Misting Bottle', 'Plant Stakes',
            'Garden Trowel', 'Organic Mulch', 'Pest Control Spray', 'Plant Labels'
        ]);

        $price = fake()->randomFloat(2, 5, 200);
        $hasSale = fake()->boolean(30); // 30% chance of sale price

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->paragraph(5),
            'short_description' => fake()->sentence(10),
            'price' => $price,
            'sale_price' => $hasSale ? fake()->randomFloat(2, $price * 0.5, $price * 0.9) : null,
            'stock_quantity' => fake()->numberBetween(0, 100),
            'in_stock' => true, // Will be set correctly based on stock_quantity
            'is_featured' => fake()->boolean(20), // 20% featured
            'is_active' => true,
            'sku' => 'PRD-' . strtoupper(Str::random(8)),
            'images' => [
                'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=500',
                'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?w=500'
            ],
            'care_instructions' => [
                'Keep in dry place',
                'Store at room temperature',
                'Use within 6 months of opening'
            ],
            'plant_characteristics' => null,
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
            'in_stock' => false,
        ]);
    }

    /**
     * Indicate that the product is on sale.
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $discount = fake()->randomFloat(2, 10, 50);
            return [
                'sale_price' => $attributes['price'] * (1 - $discount / 100),
            ];
        });
    }
}

