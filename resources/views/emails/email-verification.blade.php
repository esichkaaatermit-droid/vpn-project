<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Подтверждение email</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #1a73e8;">Подтверждение email</h1>
    <p>Вы запросили привязку email <strong>{{ $email }}</strong> к вашему Telegram-аккаунту.</p>
    <p>Нажмите кнопку ниже для подтверждения:</p>
    <p style="margin: 30px 0;">
        <a href="{{ $confirmUrl }}" style="display: inline-block; padding: 12px 24px; background: #1a73e8; color: white; text-decoration: none; border-radius: 6px;">Подтвердить email</a>
    </p>
    <p style="font-size: 14px; color: #666;">Ссылка действительна 24 часа.</p>
    <p style="font-size: 14px; color: #666;">Если вы не запрашивали привязку, проигнорируйте это письмо.</p>
</body>
</html>
