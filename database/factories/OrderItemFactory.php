<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly choose between product or plant
        $itemType = fake()->randomElement(['App\Models\Product', 'App\Models\Plant']);
        $itemId = $itemType === 'App\Models\Product'
            ? Product::factory()
            : Plant::factory();

        $quantity = fake()->numberBetween(1, 5);
        $price = fake()->randomFloat(2, 10, 200);

        return [
            'order_id' => Order::factory(),
            'item_type' => $itemType,
            'item_id' => $itemId,
            'quantity' => $quantity,
            'price' => $price, // Price at time of purchase
        ];
    }

    /**
     * Indicate that the item is a product.
     */
    public function product(): static
    {
        return $this->state(function (array $attributes) {
            $product = Product::factory()->create();
            return [
                'item_type' => 'App\Models\Product',
                'item_id' => $product->id,
                'price' => $product->sale_price ?? $product->price,
            ];
        });
    }

    /**
     * Indicate that the item is a plant.
     */
    public function plant(): static
    {
        return $this->state(function (array $attributes) {
            $plant = Plant::factory()->create();
            return [
                'item_type' => 'App\Models\Plant',
                'item_id' => $plant->id,
                'price' => $plant->sale_price ?? $plant->price,
            ];
        });
    }
}

