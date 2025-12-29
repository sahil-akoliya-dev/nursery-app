<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Don't Forget Your Items!</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2d5016;">🌱 Don't Forget Your Items!</h1>
        
        <p>Dear {{ $user->name }},</p>
        
        <p>We noticed you left some items in your cart. Don't miss out on these beautiful plants!</p>
        
        <h3 style="color: #2d5016;">Items in Your Cart:</h3>
        <ul>
            @foreach($cartItems as $item)
                <li>{{ $item->item->name ?? 'Item' }} - ${{ number_format($item->price, 2) }}</li>
            @endforeach
        </ul>
        
        <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
            <p style="font-size: 1.2em; margin: 0;"><strong>Total: ${{ number_format($cartTotal, 2) }}</strong></p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.frontend_url') }}/pages/cart.html" style="background: #2d5016; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">Complete Your Order</a>
        </div>
        
        <p>Best regards,<br>The Nursery App Team</p>
    </div>
</body>
</html>

