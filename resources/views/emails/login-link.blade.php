<!DOCTYPE html>
<html>
<head>
    <title>Your Login Link</title>
</head>
<body>
    <h1>Login Link</h1>
    <p>Click the link below to log in to your account. This link will expire in 15 minutes.</p>

    <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none;">Log In</a>
    
    <p>If you didn't request this login link, you can safely ignore this email.</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>