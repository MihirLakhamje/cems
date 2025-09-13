<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Registration Confirmation</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>
    <p>You have successfully registered for the event: <strong>{{ $event->name }}</strong>.</p>
    <p>The event will take place from <strong>{{ $event->start_date->format('d-m-Y') }}</strong> to <strong>{{ $event->end_date->format('d-m-Y') }}</strong>.</p>
    <p>Thank you for registering! This email serves as your official confirmation.</p>

    <hr style="margin:20px 0;">

    <p style="font-size:0.9em; color:#555;">
        <strong>Security Notice:</strong> Please be aware of fake emails claiming to be from this system. 
        The official confirmation will always come from <em>{{ config('mail.from.address') }}</em>. 
        Do not share your personal information or make any payments based on suspicious emails.
    </p>
</body>
</html>
