<?php

namespace Tests\Feature\Feature\Integration;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test complete checkout flow from cart to order
     */
    public function test_complete_checkout_flow(): void
    {
        // 1. Create user and authenticate
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // 2. Create products
        $product1 = Product::factory()->create(['price' => 25.00, 'stock' => 10]);
        $product2 = Product::factory()->create(['price' => 15.00, 'stock' => 5]);

        // 3. Add products to cart
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cart/add', [
                'product_id' => $product1->id,
                'quantity' => 2
            ])
            ->assertStatus(200);

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cart/add', [
                'product_id' => $product2->id,
                'quantity' => 1
            ])
            ->assertStatus(200);

        // 4. Verify cart contents
        $cartResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/cart')
            ->assertStatus(200);

        $this->assertEquals(2, count($cartResponse->json('data.items')));

        // 5. Create shipping address
        $address = Address::factory()->create(['user_id' => $user->id]);

        // 6. Create order
        $orderResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/orders', [
                'address_id' => $address->id,
                'payment_method' => 'credit_card'
            ])
            ->assertStatus(201)
            ->assertJson(['success' => true]);

        // 7. Verify order was created
        $orderId = $orderResponse->json('data.id');
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        // 8. Verify order items
        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'product_id' => $product1->id,
            'quantity' => 2
        ]);

        // 9. Verify cart was cleared
        $clearedCart = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/cart')
            ->assertStatus(200);

        $this->assertEquals(0, count($clearedCart->json('data.items')));

        // 10. Verify inventory was updated
        $this->assertDatabaseHas('products', [
            'id' => $product1->id,
            'stock' => 8 // 10 - 2
        ]);
    }

    /**
     * Test checkout fails with insufficient stock
     */
    public function test_checkout_fails_with_insufficient_stock(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $product = Product::factory()->create(['price' => 25.00, 'stock' => 1]);

        // Try to add more than available stock
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cart/add', [
                'product_id' => $product->id,
                'quantity' => 5
            ]);

        // Should fail or limit to available stock
        $this->assertTrue(
            $response->status() === 422 || $response->status() === 400
        );
    }
}
