<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use App\Mail\AbandonedCartReminder;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class AbandonedCartReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_abandoned_cart_email_can_be_sent(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $cartItems = new Collection([
            CartItem::factory()->make([
                'item_id' => $product->id,
                'item_type' => Product::class,
            ]),
        ]);

        Mail::fake();

        Mail::to($user->email)->send(new AbandonedCartReminder($user, $cartItems, 50.00));

        Mail::assertSent(AbandonedCartReminder::class);
    }
}

