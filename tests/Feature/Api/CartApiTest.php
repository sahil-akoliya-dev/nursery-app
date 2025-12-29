<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CartApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_active' => true,
            'price' => 25.00,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_cart(): void
    {
        $response = $this->getJson('/api/cart');
        
        $response->assertStatus(401);
    }

    public function test_can_get_empty_cart(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/cart');
        
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'items' => [],
                'item_count' => 0,
            ],
        ]);
    }

    public function test_can_add_item_to_cart(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/cart/add', [
            'item_id' => $this->product->id,
            'item_type' => 'product',
            'quantity' => 2,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Item added to cart successfully.',
        ]);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cannot_add_item_with_invalid_data(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/cart/add', [
            'item_id' => $this->product->id,
            'item_type' => 'product',
            'quantity' => 0, // Invalid
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_add_item_exceeding_stock(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/cart/add', [
            'item_id' => $this->product->id,
            'item_type' => 'product',
            'quantity' => 100,
        ]);

        $response->assertStatus(400);
    }

    public function test_can_get_cart_with_items(): void
    {
        Sanctum::actingAs($this->user);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'subtotal',
                'tax',
                'shipping',
                'total',
                'item_count',
            ],
        ]);
        
        $this->assertCount(1, $response->json('data.items'));
    }

    public function test_can_update_cart_item_quantity(): void
    {
        Sanctum::actingAs($this->user);

        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $response = $this->putJson("/api/cart/update/{$cartItem->id}", [
            'quantity' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    public function test_can_remove_item_from_cart(): void
    {
        Sanctum::actingAs($this->user);

        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $response = $this->deleteJson("/api/cart/remove/{$cartItem->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_can_clear_cart(): void
    {
        Sanctum::actingAs($this->user);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $response = $this->deleteJson('/api/cart/clear');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(0, CartItem::where('user_id', $this->user->id)->count());
    }

    public function test_can_get_cart_count(): void
    {
        Sanctum::actingAs($this->user);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 3,
            'price' => $this->product->price,
        ]);

        $response = $this->getJson('/api/cart/count');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'total_quantity' => 3,
                'unique_items' => 1,
            ],
        ]);
    }

    public function test_cannot_access_other_user_cart(): void
    {
        $otherUser = User::factory()->create();
        
        $cartItem = CartItem::create([
            'user_id' => $otherUser->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/cart/remove/{$cartItem->id}");

        $response->assertStatus(404); // Not found (other user's item)
    }
}

