<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CartService;
use App\Models\User;
use App\Models\Product;
use App\Models\Plant;
use App\Models\CartItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;
    protected User $user;
    protected Product $product;
    protected Plant $plant;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->cartService = new CartService();
        
        // Create test category
        $this->category = Category::factory()->create();
        
        // Create test product
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_active' => true,
            'price' => 25.00,
        ]);
        
        // Create test plant
        $this->plant = Plant::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 5,
            'in_stock' => true,
            'is_active' => true,
            'price' => 15.00,
        ]);
        
        // Create test user
        $this->user = User::factory()->create();
    }

    public function test_can_add_item_to_cart(): void
    {
        $cart = $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            2
        );

        $this->assertArrayHasKey('items', $cart);
        $this->assertCount(1, $cart['items']);
        $this->assertEquals(2, $cart['items'][0]['quantity']);
        $this->assertEquals($this->product->id, $cart['items'][0]['item_id']);
    }

    public function test_can_add_plant_to_cart(): void
    {
        $cart = $this->cartService->addItem(
            $this->user->id,
            $this->plant->id,
            'plant',
            1
        );

        $this->assertCount(1, $cart['items']);
        $this->assertEquals($this->plant->id, $cart['items'][0]['item_id']);
    }

    public function test_cannot_add_item_with_invalid_quantity_zero(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            0
        );
    }

    public function test_cannot_add_item_with_invalid_quantity_negative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            -1
        );
    }

    public function test_cannot_add_item_exceeding_max_quantity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be between 1 and 100');
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            101
        );
    }

    public function test_cannot_add_item_exceeding_stock(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('insufficient quantity');
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            100
        );
    }

    public function test_cannot_add_inactive_item(): void
    {
        $this->product->update(['is_active' => false]);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('no longer available');
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            1
        );
    }

    public function test_cannot_add_out_of_stock_item(): void
    {
        $this->product->update(['in_stock' => false, 'stock_quantity' => 0]);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('insufficient quantity');
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            1
        );
    }

    public function test_increments_quantity_when_item_already_in_cart(): void
    {
        // Add item first time
        $this->cartService->addItem($this->user->id, $this->product->id, 'product', 2);
        
        // Add same item again
        $cart = $this->cartService->addItem($this->user->id, $this->product->id, 'product', 3);

        $this->assertCount(1, $cart['items']);
        $this->assertEquals(5, $cart['items'][0]['quantity']);
    }

    public function test_updates_price_when_item_price_changes(): void
    {
        // Add item at original price
        $this->cartService->addItem($this->user->id, $this->product->id, 'product', 1);
        
        // Change product price
        $this->product->update(['price' => 30.00]);
        
        // Clear cache to ensure fresh calculation
        Cache::flush();
        
        // Get cart - should update price
        $cart = $this->cartService->getCart($this->user->id);
        
        // Price should be updated to new price
        $this->assertEquals(30.00, $cart['items'][0]['price']);
    }

    public function test_can_update_cart_item_quantity(): void
    {
        // Add item first
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $cart = $this->cartService->updateQuantity(
            $this->user->id,
            $cartItem->id,
            3
        );

        $this->assertEquals(3, $cart['items'][0]['quantity']);
    }

    public function test_cannot_update_quantity_to_zero(): void
    {
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        
        $this->cartService->updateQuantity($this->user->id, $cartItem->id, 0);
    }

    public function test_cannot_update_quantity_exceeding_stock(): void
    {
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Only');
        
        $this->cartService->updateQuantity($this->user->id, $cartItem->id, 100);
    }

    public function test_cannot_update_nonexistent_cart_item(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart item not found');
        
        $this->cartService->updateQuantity($this->user->id, 99999, 2);
    }

    public function test_can_remove_item_from_cart(): void
    {
        // Add item first
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $cart = $this->cartService->removeItem(
            $this->user->id,
            $cartItem->id
        );

        $this->assertCount(0, $cart['items']);
    }

    public function test_cannot_remove_nonexistent_cart_item(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart item not found');
        
        $this->cartService->removeItem($this->user->id, 99999);
    }

    public function test_can_clear_cart(): void
    {
        // Add items
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->plant->id,
            'item_type' => Plant::class,
            'quantity' => 1,
            'price' => $this->plant->price,
        ]);

        $this->cartService->clearCart($this->user->id);

        $this->assertEquals(0, CartItem::where('user_id', $this->user->id)->count());
    }

    public function test_cart_calculates_totals_correctly(): void
    {
        Cache::flush(); // Clear cache to ensure fresh calculation
        
        $expectedSubtotal = 2 * $this->product->price;
        $expectedTax = round($expectedSubtotal * 0.08, 2);
        $expectedShipping = $expectedSubtotal >= 50 ? 0 : 9.99;
        $expectedTotal = round($expectedSubtotal + $expectedTax + $expectedShipping, 2);
        
        $cart = $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            2
        );

        $this->assertArrayHasKey('subtotal', $cart);
        $this->assertArrayHasKey('tax', $cart);
        $this->assertArrayHasKey('shipping', $cart);
        $this->assertArrayHasKey('total', $cart);
        $this->assertGreaterThan(0, $cart['total']);
        
        // Verify calculations match expected values
        $this->assertEquals($expectedSubtotal, $cart['subtotal']);
        $this->assertEquals($expectedTax, $cart['tax']);
        $this->assertEquals($expectedShipping, $cart['shipping']);
        $this->assertEquals($expectedTotal, $cart['total']);
    }

    public function test_cart_applies_free_shipping_over_threshold(): void
    {
        // Create expensive product
        $expensiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 100.00,
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_active' => true,
        ]);

        $cart = $this->cartService->addItem(
            $this->user->id,
            $expensiveProduct->id,
            'product',
            1
        );

        // Should have free shipping (subtotal >= 50)
        // Free shipping threshold is >= 50, so 100 should get free shipping
        $this->assertEquals(0, $cart['shipping']);
    }

    public function test_cart_charges_shipping_below_threshold(): void
    {
        // Create cheap product
        $cheapProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'price' => 10.00,
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_active' => true,
        ]);

        $cart = $this->cartService->addItem(
            $this->user->id,
            $cheapProduct->id,
            'product',
            1
        );

        // Should charge shipping (subtotal < 50)
        $this->assertEquals(9.99, $cart['shipping']);
    }

    public function test_cart_count_returns_correct_values(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 3,
            'price' => $this->product->price,
        ]);

        $count = $this->cartService->getCartCount($this->user->id);

        $this->assertEquals(3, $count['total_quantity']);
        $this->assertEquals(1, $count['unique_items']);
    }

    public function test_cart_excludes_expired_items(): void
    {
        // Create expired cart item (older than 30 days)
        // Use DB::table to directly insert with old timestamp to bypass Eloquent timestamps
        \DB::table('cart_items')->insert([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
            'created_at' => now()->subDays(31)->format('Y-m-d H:i:s'), // Older than 30 days
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        // Clear cache to ensure fresh query
        Cache::flush();

        // Get cart - should exclude expired items (filtered by query)
        // The query uses '>=' which means items created_at >= (now - 30 days)
        // Items 31 days old have created_at < (now - 30 days), so they're excluded
        $cart = $this->cartService->getCart($this->user->id);
        
        // Verify expired item is excluded
        $this->assertCount(0, $cart['items']);
    }

    public function test_cart_handles_deleted_items(): void
    {
        // Add item to cart
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        // Delete the product
        $this->product->delete();

        // Get cart - should remove deleted item
        $cart = $this->cartService->getCart($this->user->id);
        
        $this->assertCount(0, $cart['items']);
    }

    public function test_cart_handles_inactive_items(): void
    {
        // Add item to cart
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        // Make product inactive
        $this->product->update(['is_active' => false]);

        // Get cart - should exclude inactive items
        $cart = $this->cartService->getCart($this->user->id);
        
        $this->assertCount(0, $cart['items']);
    }

    public function test_cart_adjusts_quantity_when_stock_reduces(): void
    {
        // Add item with quantity 5
        $cartItem = CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 5,
            'price' => $this->product->price,
        ]);

        // Reduce stock to 3
        $this->product->update(['stock_quantity' => 3]);

        // Get cart - should adjust quantity
        $cart = $this->cartService->getCart($this->user->id);
        
        $this->assertEquals(3, $cart['items'][0]['quantity']);
    }

    public function test_cart_uses_sale_price_when_available(): void
    {
        // Set sale price
        $this->product->update(['sale_price' => 20.00, 'price' => 25.00]);

        $cart = $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            1
        );

        // Should use sale price
        $this->assertEquals(20.00, $cart['items'][0]['price']);
    }

    public function test_cart_cannot_exceed_max_items_limit(): void
    {
        // Create 50 products
        for ($i = 0; $i < 50; $i++) {
            $product = Product::factory()->create([
                'category_id' => $this->category->id,
                'stock_quantity' => 10,
                'in_stock' => true,
                'is_active' => true,
            ]);
            
            CartItem::create([
                'user_id' => $this->user->id,
                'item_id' => $product->id,
                'item_type' => Product::class,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        // Try to add one more item
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart limit reached');
        
        $this->cartService->addItem(
            $this->user->id,
            $this->product->id,
            'product',
            1
        );
    }

    public function test_cart_caches_results(): void
    {
        Cache::flush();
        
        // First call
        $cart1 = $this->cartService->getCart($this->user->id);
        
        // Should be cached
        $this->assertTrue(Cache::has("cart_user_{$this->user->id}"));
        
        // Second call should use cache
        $cart2 = $this->cartService->getCart($this->user->id);
        
        $this->assertEquals($cart1, $cart2);
    }
}
