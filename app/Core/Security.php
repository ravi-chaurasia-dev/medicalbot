<?php

declare(strict_types=1);

namespace App\Core;

final class Security
{
    public static function apply(): void
    {
        // Prevent MIME sniffing
        header('X-Content-Type-Options: nosniff');
        // Clickjacking protection
        header('X-Frame-Options: SAMEORIGIN');
        // XSS protection
        header('X-XSS-Protection: 1; mode=block');
        // Content Security Policy (basic)
        $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:; connect-src 'self' https:;";
        header('Content-Security-Policy: ' . $csp);
        // Force secure transport in production
        $env = Config::get('app.env', 'production');
        if ($env === 'production') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
    }
}
