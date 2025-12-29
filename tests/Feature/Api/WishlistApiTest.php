<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\WishlistItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class WishlistApiTest extends TestCase
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
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_wishlist(): void
    {
        $response = $this->getJson('/api/wishlist');
        
        $response->assertStatus(401);
    }

    public function test_can_get_empty_wishlist(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/wishlist');

        $response->assertStatus(200);
        $data = $response->json('data') ?? $response->json();
        $this->assertTrue(
            empty($data['items'] ?? []) || 
            empty($data ?? []) ||
            count($data['items'] ?? $data ?? []) === 0
        );
    }

    public function test_can_add_item_to_wishlist(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/wishlist/add', [
            'item_id' => $this->product->id,
            'item_type' => 'product',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('wishlist_items', [
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
        ]);
    }

    public function test_can_remove_item_from_wishlist(): void
    {
        Sanctum::actingAs($this->user);

        $wishlistItem = WishlistItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
        ]);

        $response = $this->deleteJson("/api/wishlist/remove/{$wishlistItem->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('wishlist_items', ['id' => $wishlistItem->id]);
    }

    public function test_can_clear_wishlist(): void
    {
        Sanctum::actingAs($this->user);

        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
        ]);

        $response = $this->deleteJson('/api/wishlist/clear');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals(0, WishlistItem::where('user_id', $this->user->id)->count());
    }

    public function test_can_check_if_item_in_wishlist(): void
    {
        Sanctum::actingAs($this->user);

        WishlistItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
        ]);

        $response = $this->getJson('/api/wishlist/check?item_id=' . $this->product->id . '&item_type=product');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'in_wishlist' => true
            ]
        ]);
    }
}

