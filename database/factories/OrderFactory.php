<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 20, 500);
        $tax = round($subtotal * 0.08, 2);
        $shipping = 9.99;
        $total = round($subtotal + $tax + $shipping, 2);

        $shippingAddress = [
            'line1' => fake()->streetAddress(),
            'line2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
        ];

        $status = fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']);
        $paymentStatus = fake()->randomElement(['pending', 'paid', 'failed', 'refunded']);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'shipping_amount' => $shipping,
            'total_amount' => $total,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'payment_method' => fake()->randomElement(['stripe', 'paypal', 'cod']),
            'payment_transaction_id' => $paymentStatus === 'paid' ? 'txn_' . strtoupper(Str::random(24)) : null,
            'shipping_address' => $shippingAddress,
            'billing_address' => $shippingAddress, // Same as shipping for simplicity
            'notes' => fake()->optional()->sentence(),
            'cancelled_at' => $status === 'cancelled' ? now()->subDays(fake()->numberBetween(1, 30)) : null,
            'cancellation_reason' => $status === 'cancelled' ? fake()->sentence() : null,
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    /**
     * Indicate that the order is completed.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'payment_status' => $attributes['payment_status'] === 'paid' ? 'refunded' : $attributes['payment_status'],
                'cancelled_at' => now()->subDays(fake()->numberBetween(1, 30)),
                'cancellation_reason' => fake()->sentence(),
            ];
        });
    }
}
