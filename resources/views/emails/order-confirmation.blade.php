<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2d5016;">🌱 Order Confirmation</h1>
        
        <p>Dear {{ $order->user->name }},</p>
        
        <p>Thank you for your order! We're excited to prepare your plants for delivery.</p>
        
        <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h2 style="margin-top: 0; color: #2d5016;">Order Details</h2>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
        
        <h3 style="color: #2d5016;">Order Items</h3>
        <ul>
            @foreach($order->orderItems as $item)
                <li>{{ $item->item->name }} - Quantity: {{ $item->quantity }} - ${{ number_format($item->price * $item->quantity, 2) }}</li>
            @endforeach
        </ul>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #ddd;">
            <p><strong>Shipping Address:</strong></p>
            <p>
                {{ $order->shipping_address['first_name'] ?? '' }} {{ $order->shipping_address['last_name'] ?? '' }}<br>
                {{ $order->shipping_address['address'] ?? '' }}<br>
                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['zip'] ?? '' }}
            </p>
        </div>
        
        <p style="margin-top: 30px;">We'll send you another email when your order ships!</p>
        
        <p>Best regards,<br>The Nursery App Team</p>
    </div>
</body>
</html>

