<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\OrderService;
use App\Models\User;
use App\Models\Product;
use App\Models\Plant;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\LoyaltyPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $orderService;
    protected User $user;
    protected Product $product;
    protected Plant $plant;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
        
        $this->orderService = new OrderService();
        
        $this->category = Category::factory()->create();
        
        $this->product = Product::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_active' => true,
            'price' => 50.00,
        ]);
        
        $this->plant = Plant::factory()->create([
            'category_id' => $this->category->id,
            'stock_quantity' => 5,
            'in_stock' => true,
            'is_active' => true,
            'price' => 30.00,
        ]);
        
        $this->user = User::factory()->create();
    }

    public function test_can_create_order_from_cart(): void
    {
        // Add item to cart
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $orderData = [
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
            'notes' => 'Test order',
        ];

        $order = $this->orderService->createOrder($this->user->id, $orderData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertNotNull($order->order_number);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals(1, $order->orderItems->count()); // One cart item = one order item
        $this->assertEquals(2, $order->orderItems->first()->quantity); // But quantity is 2
        $this->assertEquals(0, CartItem::where('user_id', $this->user->id)->count()); // Cart cleared
    }

    public function test_order_creation_calculates_totals_correctly(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $order = $this->orderService->createOrder($this->user->id, $orderData);

        // Subtotal: 2 * 50 = 100
        $this->assertEquals(100.00, $order->subtotal);
        // Tax: 8% of 100 = 8
        $this->assertEquals(8.00, $order->tax_amount);
        // Shipping: Free (>= 50)
        $this->assertEquals(0, $order->shipping_amount);
        // Total: 100 + 8 + 0 = 108
        $this->assertEquals(108.00, $order->total_amount);
    }

    public function test_order_creation_fails_with_empty_cart(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart is empty');

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $this->orderService->createOrder($this->user->id, $orderData);
    }

    public function test_order_creation_fails_with_invalid_items(): void
    {
        // Add inactive item
        $inactiveProduct = Product::factory()->create([
            'category_id' => $this->category->id,
            'is_active' => false,
            'stock_quantity' => 10,
        ]);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $inactiveProduct->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => 50.00,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('no longer available');

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $this->orderService->createOrder($this->user->id, $orderData);
    }

    public function test_order_creation_fails_with_insufficient_stock(): void
    {
        // Add more items than available
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 15, // More than stock (10)
            'price' => $this->product->price,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('insufficient stock');

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $this->orderService->createOrder($this->user->id, $orderData);
    }

    public function test_order_creation_updates_inventory(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 3,
            'price' => $this->product->price,
        ]);

        $initialStock = $this->product->stock_quantity;

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $this->orderService->createOrder($this->user->id, $orderData);

        $this->product->refresh();
        $this->assertEquals($initialStock - 3, $this->product->stock_quantity);
        $this->assertEquals(7, $this->product->stock_quantity);
    }

    public function test_order_creation_marks_out_of_stock_when_needed(): void
    {
        // Set stock to exactly the quantity ordered
        $this->product->update(['stock_quantity' => 3]);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 3,
            'price' => $this->product->price,
        ]);

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $this->orderService->createOrder($this->user->id, $orderData);

        $this->product->refresh();
        $this->assertEquals(0, $this->product->stock_quantity);
        $this->assertFalse($this->product->in_stock);
    }

    public function test_order_creation_awards_loyalty_points(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => 100.00, // $100 order
        ]);

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $order = $this->orderService->createOrder($this->user->id, $orderData);

        // Should award 100 points (1 point per $1, order total ~108)
        $loyaltyPoint = LoyaltyPoint::where('user_id', $this->user->id)
            ->where('order_id', $order->id)
            ->where('type', 'earned')
            ->first();

        $this->assertNotNull($loyaltyPoint);
        $this->assertGreaterThan(0, $loyaltyPoint->points);
    }

    public function test_order_creation_sends_confirmation_email(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $this->orderService->createOrder($this->user->id, $orderData);

        Mail::assertSent(\App\Mail\OrderConfirmation::class);
    }

    public function test_order_creation_with_multiple_items(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->plant->id,
            'item_type' => Plant::class,
            'quantity' => 1,
            'price' => $this->plant->price,
        ]);

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $order = $this->orderService->createOrder($this->user->id, $orderData);

        $this->assertEquals(2, $order->orderItems->count());
    }

    public function test_order_creation_generates_unique_order_number(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $orderData = [
            'shipping_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'billing_address' => [
                'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
                'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
                'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
            ],
            'payment_method' => 'cod',
        ];

        $order1 = $this->orderService->createOrder($this->user->id, $orderData);
        
        // Create another order
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $order2 = $this->orderService->createOrder($this->user->id, $orderData);

        $this->assertNotEquals($order1->order_number, $order2->order_number);
        $this->assertStringStartsWith('ORD-', $order1->order_number);
        $this->assertStringStartsWith('ORD-', $order2->order_number);
    }

    public function test_order_creation_with_different_payment_methods(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $baseAddress = [
            'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com',
            'phone' => '1234567890', 'address' => '123 Main St', 'city' => 'New York',
            'state' => 'NY', 'zip' => '10001', 'country' => 'USA',
        ];

        // Test COD
        $orderCod = $this->orderService->createOrder($this->user->id, [
            'shipping_address' => $baseAddress,
            'billing_address' => $baseAddress,
            'payment_method' => 'cod',
        ]);
        $this->assertEquals('cod', $orderCod->payment_method);
        $this->assertEquals('pending', $orderCod->payment_status);

        // Create new cart for next test
        CartItem::create([
            'user_id' => $this->user->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        // Test Stripe (placeholder)
        $orderStripe = $this->orderService->createOrder($this->user->id, [
            'shipping_address' => $baseAddress,
            'billing_address' => $baseAddress,
            'payment_method' => 'stripe',
        ]);
        $this->assertEquals('stripe', $orderStripe->payment_method);
    }

    public function test_can_cancel_order_within_deadline(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $result = $this->orderService->cancelOrder($order->id, $this->user->id, 'Test cancellation');

        $this->assertTrue($result);
        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
        $this->assertNotNull($order->cancelled_at);
        $this->assertEquals('Test cancellation', $order->cancellation_reason);
    }

    public function test_cannot_cancel_order_after_deadline(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'created_at' => now()->subHours(25), // 25 hours ago
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('24 hours');

        $this->orderService->cancelOrder($order->id, $this->user->id);
    }

    public function test_cannot_cancel_order_not_owned_by_user(): void
    {
        $otherUser = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $otherUser->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Order not found');

        $this->orderService->cancelOrder($order->id, $this->user->id);
    }

    public function test_cannot_cancel_order_in_wrong_status(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'delivered', // Already delivered
            'created_at' => now(),
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('cannot be cancelled');

        $this->orderService->cancelOrder($order->id, $this->user->id);
    }

    public function test_cancellation_restores_inventory(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        // Create order item
        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'item_id' => $this->product->id,
            'item_type' => Product::class,
            'quantity' => 3,
            'price' => $this->product->price,
        ]);

        $initialStock = $this->product->stock_quantity;
        $this->product->update(['stock_quantity' => $initialStock - 3]); // Simulate order

        $this->orderService->cancelOrder($order->id, $this->user->id);

        $this->product->refresh();
        $this->assertEquals($initialStock, $this->product->stock_quantity);
        $this->assertTrue($this->product->in_stock);
    }

    public function test_cancellation_reverses_loyalty_points(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'total_amount' => 100.00,
            'created_at' => now(),
        ]);

        // Create loyalty points from order
        LoyaltyPoint::create([
            'user_id' => $this->user->id,
            'points' => 100,
            'type' => 'earned',
            'source' => 'purchase',
            'order_id' => $order->id,
            'points_balance' => 100,
        ]);

        $this->orderService->cancelOrder($order->id, $this->user->id);

        // Should have redemption record
        $redemption = LoyaltyPoint::where('user_id', $this->user->id)
            ->where('order_id', $order->id)
            ->where('type', 'redeemed')
            ->first();

        $this->assertNotNull($redemption);
        $this->assertEquals(-100, $redemption->points);
    }

    public function test_cancellation_sets_payment_status_to_refunded_when_paid(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_status' => 'paid',
            'created_at' => now(),
        ]);

        $this->orderService->cancelOrder($order->id, $this->user->id);

        $order->refresh();
        $this->assertEquals('refunded', $order->payment_status);
    }
}
