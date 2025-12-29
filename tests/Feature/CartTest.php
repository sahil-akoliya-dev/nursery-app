<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Plant;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $plant;
    protected $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->plant = Plant::factory()->create(['price' => 25.00]);
        $this->cartService = app(CartService::class);
    }

    public function test_can_view_cart_page()
    {
        $response = $this->get(route('cart.show'));
        $response->assertStatus(200);
        $response->assertViewIs('cart.show');
    }

    public function test_can_add_item_to_cart()
    {
        $response = $this->post(route('cart.add'), [
            'plant_id' => $this->plant->id,
            'quantity' => 2
        ]);
        
        $response->assertRedirect(route('cart.show'));
        $response->assertSessionHas('success', 'Added to cart');
        
        $this->assertDatabaseHas('cart_items', [
            'plant_id' => $this->plant->id,
            'quantity' => 2
        ]);
    }

    public function test_can_update_cart_item_quantity()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'plant_id' => $this->plant->id,
            'quantity' => 1,
            'unit_price' => $this->plant->price
        ]);
        
        $response = $this->post(route('cart.update', $cartItem), [
            'quantity' => 3
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Cart updated');
        
        $cartItem->refresh();
        $this->assertEquals(3, $cartItem->quantity);
    }

    public function test_can_remove_item_from_cart()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'plant_id' => $this->plant->id
        ]);
        
        $response = $this->post(route('cart.remove', $cartItem));
        
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Item removed');
        
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_cart_service_calculates_totals_correctly()
    {
        $this->actingAs($this->user);
        $cart = $this->cartService->getOrCreateCurrentCart();
        $this->cartService->addItemLegacy($this->plant->id, 2);
        
        $cart->refresh();
        $cart->calculateTotal();
        $this->assertEquals(50.00, $cart->subtotal);
        $this->assertEquals(50.00, $cart->grand_total);
    }

    public function test_cart_persists_for_authenticated_users()
    {
        $this->actingAs($this->user);
        
        $cart = $this->cartService->getOrCreateCurrentCart();
        $this->assertNotNull($cart);
        $this->assertEquals($this->user->id, $cart->user_id);
    }

    public function test_cart_persists_for_guest_users_via_session()
    {
        $cart = $this->cartService->getOrCreateCurrentCart();
        $this->assertNotNull($cart->session_id);
        $this->assertNull($cart->user_id);
    }
}
