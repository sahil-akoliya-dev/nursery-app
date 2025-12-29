<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Plant Care Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2d5016;">🌱 Plant Care Reminder</h1>
        
        <p>Dear {{ $reminder->user->name }},</p>
        
        <p>This is a friendly reminder about your plant care task:</p>
        
        <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h2 style="margin-top: 0; color: #2d5016;">{{ $reminder->title }}</h2>
            @if($reminder->description)
                <p>{{ $reminder->description }}</p>
            @endif
            <p><strong>Due Date:</strong> {{ $reminder->scheduled_date->format('F d, Y') }}</p>
            <p><strong>Type:</strong> {{ ucfirst($reminder->reminder_type) }}</p>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.frontend_url') }}/pages/plant-care-reminders.html" style="background: #2d5016; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;">View Reminders</a>
        </div>
        
        <p>Keep your plants healthy and happy! 🌿</p>
        
        <p>Best regards,<br>The Nursery App Team</p>
    </div>
</body>
</html>

