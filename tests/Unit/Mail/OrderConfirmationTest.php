<?php

namespace Tests\Unit\Mail;

use Tests\TestCase;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class OrderConfirmationTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_confirmation_email_can_be_sent(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        Mail::fake();

        Mail::to($user->email)->send(new OrderConfirmation($order));

        Mail::assertSent(OrderConfirmation::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;
        });
    }

    public function test_order_confirmation_email_has_correct_subject(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST123',
        ]);

        $mailable = new OrderConfirmation($order);

        $this->assertEquals("Order Confirmation - {$order->order_number}", $mailable->build()->subject);
    }
}

