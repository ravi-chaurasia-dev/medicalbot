<?php

declare(strict_types=1);

return [
    'name' => env('APP_NAME', 'MediAI'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'key' => env('APP_KEY', 'base64:change-me'),
    'base_path' => env('BASE_PATH', dirname(__DIR__)),
    'log' => [
        'channel' => env('LOG_CHANNEL', 'single'),
        'level' => env('LOG_LEVEL', 'debug'),
        'path' => env('LOG_PATH', dirname(__DIR__) . '/storage/logs/app.log'),
    ],
    'session' => [
        'driver' => env('SESSION_DRIVER', 'file'),
        'lifetime' => (int) env('SESSION_LIFETIME', 120),
        'cookie_name' => env('SESSION_COOKIE_NAME', 'mediai_session'),
        'secure' => filter_var(env('SESSION_SECURE', false), FILTER_VALIDATE_BOOLEAN),
        'same_site' => env('SESSION_SAME_SITE', 'Lax'),
    ],
    'csrf' => [
        'token_name' => env('CSRF_TOKEN_NAME', '_csrf_token'),
        'token_length' => (int) env('CSRF_TOKEN_LENGTH', 32),
    ],
    'mail' => [
        'mailer' => env('MAIL_MAILER', 'smtp'),
        'host' => env('MAIL_HOST', 'localhost'),
        'port' => (int) env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME', ''),
        'password' => env('MAIL_PASSWORD', ''),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'from_name' => env('MAIL_FROM_NAME', 'MediAI'),
        'from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@mediai.local'),
    ],
];
