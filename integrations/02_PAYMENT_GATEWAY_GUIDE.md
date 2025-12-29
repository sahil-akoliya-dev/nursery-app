# Payment Gateway Integration Guide (Stripe & PayPal)
**Priority:** CRITICAL (Required for Shop functionality)
**Time Required:** 1-2 hours
**Difficulty:** Medium

---

## 📋 What You'll Get

After this integration:
- ✅ Accept credit card payments via Stripe
- ✅ Accept PayPal payments
- ✅ Secure payment processing
- ✅ Order confirmation emails
- ✅ Payment success/failure handling
- ✅ Refund capability

---

## 🎯 Overview

Your app already has:
- ✅ Order creation logic
- ✅ `OrderService.php` with placeholder methods
- ❌ Missing: Stripe/PayPal SDKs and implementation

We'll implement:
1. **Stripe** - Credit/Debit card payments (Recommended)
2. **PayPal** - PayPal account payments (Optional)

---

## 💳 PART 1: STRIPE INTEGRATION

### Why Stripe?
- Easy to integrate
- Great developer experience
- PCI compliant (secure)
- Supports all major credit cards
- Best for modern web apps

---

## 📦 Step 1: Install Stripe PHP SDK

```bash
composer require stripe/stripe-php
```

---

## 🔑 Step 2: Get Stripe API Keys

### A. Create Stripe Account

1. **Go to:** https://dashboard.stripe.com/register
2. **Sign up** with email
3. **Complete** account setup

### B. Get API Keys

1. **Dashboard:** https://dashboard.stripe.com/test/apikeys
2. **You'll see:**
   - **Publishable key** (starts with `pk_test_`)
   - **Secret key** (starts with `sk_test_`)

3. **Copy both keys**

**Note:** These are TEST keys. For production, activate your account and use LIVE keys.

---

## ⚙️ Step 3: Configure Environment

Add to `.env`:

```env
# Stripe Payment Gateway
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=  # We'll add this later
```

Add to `config/services.php`:

```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook' => [
        'secret' => env('STRIPE_WEBHOOK_SECRET'),
        'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
    ],
],
```

---

## 🛠️ Step 4: Update OrderService.php

Find `app/Services/OrderService.php` and update the `processStripePayment` method:

```php
<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class OrderService
{
    /**
     * Process Stripe payment
     *
     * @param Order $order
     * @param array $paymentData
     * @return array
     */
    public function processStripePayment(Order $order, array $paymentData)
    {
        try {
            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total_amount * 100, // Convert to cents
                'currency' => 'usd', // Change to your currency
                'description' => "Order #{$order->order_number}",
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_email' => $order->user->email,
                ],
                'payment_method' => $paymentData['payment_method_id'] ?? null,
                'confirm' => true,
                'return_url' => route('orders.show', $order),
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            // Check payment status
            if ($paymentIntent->status === 'succeeded') {
                // Update order
                $order->update([
                    'payment_status' => 'paid',
                    'payment_transaction_id' => $paymentIntent->id,
                    'status' => 'processing',
                ]);

                // Send confirmation email
                // Mail::to($order->user)->send(new OrderConfirmation($order));

                return [
                    'success' => true,
                    'message' => 'Payment successful!',
                    'transaction_id' => $paymentIntent->id,
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment failed. Please try again.',
                'error' => $paymentIntent->status,
            ];

        } catch (ApiErrorException $e) {
            // Log error
            \Log::error('Stripe payment failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create Stripe Payment Intent (for client-side)
     *
     * @param Order $order
     * @return array
     */
    public function createStripePaymentIntent(Order $order)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total_amount * 100,
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            return [
                'client_secret' => $paymentIntent->client_secret,
            ];

        } catch (ApiErrorException $e) {
            \Log::error('Failed to create payment intent', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Process refund
     *
     * @param Order $order
     * @param float|null $amount
     * @return array
     */
    public function processStripeRefund(Order $order, $amount = null)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $refundData = [
                'payment_intent' => $order->payment_transaction_id,
            ];

            if ($amount) {
                $refundData['amount'] = $amount * 100; // Partial refund
            }

            $refund = \Stripe\Refund::create($refundData);

            if ($refund->status === 'succeeded') {
                $order->update([
                    'payment_status' => $amount ? 'partially_refunded' : 'refunded',
                ]);

                return [
                    'success' => true,
                    'message' => 'Refund processed successfully',
                    'refund_id' => $refund->id,
                ];
            }

            return [
                'success' => false,
                'message' => 'Refund failed',
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
```

---

## 🎨 Step 5: Create Checkout Controller

Create `app/Http/Controllers/CheckoutController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Show checkout page
     */
    public function index(Order $order)
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Create payment intent for Stripe
        $paymentIntent = $this->orderService->createStripePaymentIntent($order);

        return Inertia::render('Checkout', [
            'order' => $order->load(['items', 'user']),
            'stripeKey' => config('services.stripe.key'),
            'clientSecret' => $paymentIntent['client_secret'],
        ]);
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:stripe,paypal,cod',
            'payment_method_id' => 'required_if:payment_method,stripe',
        ]);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $result = match($request->payment_method) {
            'stripe' => $this->orderService->processStripePayment($order, $request->all()),
            'paypal' => $this->orderService->processPayPalPayment($order, $request->all()),
            'cod' => $this->processCashOnDelivery($order),
            default => ['success' => false, 'message' => 'Invalid payment method'],
        };

        if ($result['success']) {
            return redirect()
                ->route('orders.confirmation', $order)
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Handle cash on delivery
     */
    private function processCashOnDelivery(Order $order)
    {
        $order->update([
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'processing',
        ]);

        return [
            'success' => true,
            'message' => 'Order placed successfully. Pay on delivery.',
        ];
    }
}
```

---

## 🛣️ Step 6: Add Routes

Add to `routes/web.php`:

```php
use App\Http\Controllers\CheckoutController;

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{order}', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/{order}/process', [CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::get('/orders/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');
});
```

---

## 🎨 Step 7: Create Frontend Checkout Component

### For React/Inertia (Recommended):

Install Stripe Elements:
```bash
npm install @stripe/stripe-js @stripe/react-stripe-js
```

Create `resources/js/Pages/Checkout.jsx`:

```jsx
import React, { useState } from 'react';
import { loadStripe } from '@stripe/stripe-js';
import { Elements, CardElement, useStripe, useElements } from '@stripe/react-stripe-js';
import { useForm } from '@inertiajs/react';

const stripePromise = loadStripe(window.stripeKey);

function CheckoutForm({ order, clientSecret }) {
    const stripe = useStripe();
    const elements = useElements();
    const [processing, setProcessing] = useState(false);
    const [error, setError] = useState(null);

    const { post } = useForm();

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!stripe || !elements) return;

        setProcessing(true);
        setError(null);

        try {
            const { error, paymentMethod } = await stripe.createPaymentMethod({
                type: 'card',
                card: elements.getElement(CardElement),
            });

            if (error) {
                setError(error.message);
                setProcessing(false);
                return;
            }

            // Submit to backend
            post(route('checkout.process', order.id), {
                payment_method: 'stripe',
                payment_method_id: paymentMethod.id,
            });

        } catch (err) {
            setError(err.message);
            setProcessing(false);
        }
    };

    return (
        <form onSubmit={handleSubmit} className="max-w-2xl mx-auto p-6">
            <h1 className="text-2xl font-bold mb-6">Complete Your Order</h1>

            {/* Order Summary */}
            <div className="bg-gray-50 p-4 rounded mb-6">
                <h2 className="font-semibold mb-2">Order Summary</h2>
                <div className="flex justify-between mb-2">
                    <span>Order Number:</span>
                    <span className="font-mono">{order.order_number}</span>
                </div>
                <div className="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>${order.subtotal}</span>
                </div>
                <div className="flex justify-between mb-2">
                    <span>Tax:</span>
                    <span>${order.tax_amount}</span>
                </div>
                <div className="flex justify-between mb-2">
                    <span>Shipping:</span>
                    <span>${order.shipping_amount}</span>
                </div>
                <div className="flex justify-between font-bold text-lg border-t pt-2">
                    <span>Total:</span>
                    <span>${order.total_amount}</span>
                </div>
            </div>

            {/* Payment Form */}
            <div className="bg-white border rounded p-4 mb-4">
                <label className="block mb-2 font-semibold">
                    Card Details
                </label>
                <CardElement
                    options={{
                        style: {
                            base: {
                                fontSize: '16px',
                                color: '#424770',
                                '::placeholder': { color: '#aab7c4' },
                            },
                            invalid: { color: '#9e2146' },
                        },
                    }}
                />
            </div>

            {error && (
                <div className="bg-red-50 text-red-600 p-3 rounded mb-4">
                    {error}
                </div>
            )}

            <button
                type="submit"
                disabled={!stripe || processing}
                className="w-full bg-green-600 text-white py-3 rounded font-semibold hover:bg-green-700 disabled:bg-gray-400"
            >
                {processing ? 'Processing...' : `Pay $${order.total_amount}`}
            </button>

            <p className="text-sm text-gray-500 text-center mt-4">
                🔒 Payments are secure and encrypted
            </p>
        </form>
    );
}

export default function Checkout({ order, stripeKey, clientSecret }) {
    window.stripeKey = stripeKey;

    return (
        <Elements stripe={stripePromise}>
            <CheckoutForm order={order} clientSecret={clientSecret} />
        </Elements>
    );
}
```

### For Blade (Alternative):

Create `resources/views/checkout.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Complete Your Order</h1>

    <!-- Order Summary -->
    <div class="bg-gray-50 p-4 rounded mb-6">
        <h2 class="font-semibold mb-2">Order Summary</h2>
        <div class="flex justify-between mb-2">
            <span>Order Number:</span>
            <span class="font-mono">{{ $order->order_number }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg border-t pt-2">
            <span>Total:</span>
            <span>${{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <!-- Payment Form -->
    <form id="payment-form">
        @csrf
        <div class="bg-white border rounded p-4 mb-4">
            <label class="block mb-2 font-semibold">Card Details</label>
            <div id="card-element"></div>
            <div id="card-errors" class="text-red-600 mt-2"></div>
        </div>

        <button
            id="submit-button"
            type="submit"
            class="w-full bg-green-600 text-white py-3 rounded font-semibold hover:bg-green-700"
        >
            Pay ${{ number_format($order->total_amount, 2) }}
        </button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';

        const { error, paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            submitButton.disabled = false;
            submitButton.textContent = 'Pay ${{ number_format($order->total_amount, 2) }}';
        } else {
            // Submit payment method to backend
            fetch('{{ route('checkout.process', $order) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_method: 'stripe',
                    payment_method_id: paymentMethod.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route('orders.confirmation', $order) }}';
                } else {
                    alert(data.message);
                    submitButton.disabled = false;
                }
            });
        }
    });
</script>
@endsection
```

---

## ✅ Step 8: Test Stripe Integration

### Test Card Numbers:

| Card Number | Description |
|-------------|-------------|
| `4242 4242 4242 4242` | Successful payment |
| `4000 0000 0000 0002` | Card declined |
| `4000 0000 0000 9995` | Insufficient funds |

**Expiry:** Any future date (e.g., `12/25`)
**CVC:** Any 3 digits (e.g., `123`)
**ZIP:** Any 5 digits (e.g., `12345`)

### Testing Steps:

1. Create an order in your app
2. Go to checkout page
3. Enter test card: `4242 4242 4242 4242`
4. Submit payment
5. Check Stripe Dashboard: https://dashboard.stripe.com/test/payments

---

## 💰 PART 2: PAYPAL INTEGRATION (OPTIONAL)

---

## 📦 Step 1: Install PayPal SDK

```bash
composer require paypal/rest-api-sdk-php
```

---

## 🔑 Step 2: Get PayPal Credentials

1. **Go to:** https://developer.paypal.com/
2. **Sign in** with PayPal account
3. **Dashboard** → "Apps & Credentials"
4. **Create App** → Enter app name
5. **Copy:**
   - Client ID
   - Secret

---

## ⚙️ Step 3: Configure PayPal

Add to `.env`:

```env
# PayPal
PAYPAL_MODE=sandbox  # Change to 'live' for production
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_SECRET=your_sandbox_secret
PAYPAL_LIVE_CLIENT_ID=your_live_client_id
PAYPAL_LIVE_SECRET=your_live_secret
```

Add to `config/services.php`:

```php
'paypal' => [
    'mode' => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'secret' => env('PAYPAL_SANDBOX_SECRET'),
    ],
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'secret' => env('PAYPAL_LIVE_SECRET'),
    ],
],
```

---

## 🛠️ Step 4: Implement PayPal in OrderService

Add to `OrderService.php`:

```php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

public function processPayPalPayment(Order $order, array $paymentData)
{
    $apiContext = $this->getPayPalApiContext();

    try {
        // Create payer
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Set amount
        $amount = new Amount();
        $amount->setTotal($order->total_amount)
               ->setCurrency('USD');

        // Set transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setDescription("Order #{$order->order_number}");

        // Set redirect URLs
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success', $order))
                     ->setCancelUrl(route('paypal.cancel', $order));

        // Create payment
        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

        $payment->create($apiContext);

        return [
            'success' => true,
            'redirect_url' => $payment->getApprovalLink(),
        ];

    } catch (\Exception $e) {
        \Log::error('PayPal payment failed', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
        ]);

        return [
            'success' => false,
            'message' => 'PayPal payment failed: ' . $e->getMessage(),
        ];
    }
}

private function getPayPalApiContext()
{
    $mode = config('services.paypal.mode');
    $config = config("services.paypal.{$mode}");

    $apiContext = new ApiContext(
        new OAuthTokenCredential($config['client_id'], $config['secret'])
    );

    $apiContext->setConfig([
        'mode' => $mode,
    ]);

    return $apiContext;
}
```

---

## 🎉 Success Criteria

### Stripe:
- [ ] Test payment with card `4242 4242 4242 4242` succeeds
- [ ] Payment appears in Stripe Dashboard
- [ ] Order status updates to "paid"
- [ ] Transaction ID stored in database

### PayPal:
- [ ] Redirect to PayPal works
- [ ] Payment completion updates order
- [ ] Sandbox transactions visible in PayPal

---

## 🐛 Common Issues

### "No API key provided"
**Fix:** Run `php artisan config:clear`

### "Invalid API Key"
**Fix:** Double-check keys in `.env`, ensure no spaces

### "Amount must be positive"
**Fix:** Check order total_amount is > 0

### Frontend doesn't load Stripe
**Fix:** Make sure `@stripe/react-stripe-js` is installed

---

## 📊 Production Checklist

- [ ] Switch to live Stripe keys
- [ ] Switch PayPal to live mode
- [ ] Enable webhooks for payment confirmation
- [ ] Test refund functionality
- [ ] Set up email notifications
- [ ] Add error logging
- [ ] Configure currency properly
- [ ] Add receipt generation

---

## 🎉 Congratulations!

You now have:
- ✅ Stripe payments working
- ✅ PayPal integration (optional)
- ✅ Secure checkout process
- ✅ Order management

**Next:** Email Service or Storage Integration?
