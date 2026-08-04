<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'MediAI'),
    'env' => env('APP_ENV', 'production'),
    'debug' => filter_var(env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'url' => env('APP_URL', 'http://localhost:8000'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => 'en',
    'session' => [
        'name' => env('SESSION_NAME', 'mediai_session'),
        'lifetime' => (int) env('SESSION_LIFETIME', 120),
    ],
    'csrf' => [
        'secret' => env('CSRF_SECRET', 'change-this-to-a-long-random-secret-value'),
    ],
    'mail' => [
        'host' => env('MAIL_HOST', 'smtp.example.com'),
        'port' => (int) env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME', ''),
        'password' => env('MAIL_PASSWORD', ''),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'from_name' => env('MAIL_FROM_NAME', 'MediAI'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),
    ],
    'logging' => [
        'channel' => env('LOG_CHANNEL', 'file'),
        'path' => env('LOG_PATH', __DIR__ . '/../storage/logs/app.log'),
    ],
];
