<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_active' => true,
            'price' => 50.00,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_order(): void
    {
        $response = $this->postJson('/api/orders', []);

        $response->assertStatus(401);
    }

    public function test_can_create_order_from_cart(): void
    {
        Sanctum::actingAs($this->user);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->postJson('/api/orders', [
            'shipping_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        // Verify cart is cleared
        $this->assertEquals(0, CartItem::where('user_id', $this->user->id)->count());
    }

    public function test_cannot_create_order_with_empty_cart(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/orders', [
            'shipping_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ]);

        $response->assertStatus(400);
    }

    public function test_can_get_user_orders(): void
    {
        Sanctum::actingAs($this->user);

        Order::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'order_number', 'status', 'total_amount'],
            ],
        ]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_can_get_single_order(): void
    {
        Sanctum::actingAs($this->user);

        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);
    }

    public function test_cannot_access_other_user_order(): void
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(404);
    }

    public function test_can_cancel_order_within_deadline(): void
    {
        Sanctum::actingAs($this->user);

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/cancel", [
            'reason' => 'Changed my mind',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
    }

    public function test_cannot_cancel_order_after_deadline(): void
    {
        Sanctum::actingAs($this->user);

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'created_at' => now()->subHours(25),
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/cancel");

        $response->assertStatus(400);
    }

    public function test_cannot_cancel_already_delivered_order(): void
    {
        Sanctum::actingAs($this->user);

        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'delivered',
            'created_at' => now(),
        ]);

        $response = $this->postJson("/api/orders/{$order->id}/cancel");

        $response->assertStatus(400);
    }

    public function test_order_creation_validates_required_fields(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/orders', [
            'shipping_address' => [
                // Missing required fields
            ],
            'payment_method' => 'cod',
        ]);

        $response->assertStatus(422);
    }
}

