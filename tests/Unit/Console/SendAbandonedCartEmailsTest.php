<?php

namespace Tests\Unit\Console;

use Tests\TestCase;
use App\Console\Commands\SendAbandonedCartEmails;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class SendAbandonedCartEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_emails_for_abandoned_carts(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Create abandoned cart (created between 3 days ago and 1 day ago)
        // Use DB::table to set exact timestamp
        \DB::table('cart_items')->insert([
            'user_id' => $user->id,
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => 25.00,
            'created_at' => now()->subDays(2)->format('Y-m-d H:i:s'), // Within the range (3 days ago to 1 day ago)
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        // Ensure no recent order exists
        Order::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(3))
            ->delete();

        $this->artisan('cart:send-reminders')
            ->expectsOutput('Checking for abandoned carts...')
            ->assertExitCode(0);

        Mail::assertSent(\App\Mail\AbandonedCartReminder::class);
    }

    public function test_command_skips_carts_with_recent_orders(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Create abandoned cart
        CartItem::create([
            'user_id' => $user->id,
            'item_id' => $product->id,
            'item_type' => Product::class,
            'quantity' => 1,
            'price' => 25.00,
            'created_at' => now()->subDays(3),
        ]);

        // Create recent order
        Order::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDay(),
        ]);

        $this->artisan('cart:send-reminders')
            ->assertExitCode(0);

        Mail::assertNothingSent();
    }
}

