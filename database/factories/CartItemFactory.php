<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use App\Models\Plant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $itemType = fake()->randomElement([Product::class, Plant::class]);
        $item = $itemType === Product::class 
            ? Product::factory()->create() 
            : Plant::factory()->create();

        return [
            'user_id' => User::factory(),
            'item_id' => $item->id,
            'item_type' => $itemType,
            'quantity' => fake()->numberBetween(1, 5),
            'price' => $item->sale_price ?? $item->price,
        ];
    }

    /**
     * Indicate that the cart item is for a product.
     */
    public function product(): static
    {
        return $this->state(function (array $attributes) {
            $product = Product::factory()->create();
            return [
                'item_id' => $product->id,
                'item_type' => Product::class,
                'price' => $product->sale_price ?? $product->price,
            ];
        });
    }

    /**
     * Indicate that the cart item is for a plant.
     */
    public function plant(): static
    {
        return $this->state(function (array $attributes) {
            $plant = Plant::factory()->create();
            return [
                'item_id' => $plant->id,
                'item_type' => Plant::class,
                'price' => $plant->sale_price ?? $plant->price,
            ];
        });
    }
}
