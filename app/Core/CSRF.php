<?php

declare(strict_types=1);

namespace App\Core;

final class CSRF
{
    public static function token(): string
    {
        $name = Config::get('app.csrf.token_name', '_csrf_token');

        if (! SessionManager::has($name)) {
            SessionManager::set($name, self::generateToken());
        }

        return (string) SessionManager::get($name);
    }

    public static function field(): string
    {
        $name = Config::get('app.csrf.token_name', '_csrf_token');

        return '<input type="hidden" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(?string $token): bool
    {
        if ($token === null || $token === '') {
            return false;
        }

        $name = Config::get('app.csrf.token_name', '_csrf_token');
        $expected = SessionManager::get($name, null);

        return is_string($expected) && hash_equals($expected, $token);
    }

    public static function regenerate(): void
    {
        $name = Config::get('app.csrf.token_name', '_csrf_token');
        SessionManager::set($name, self::generateToken());
    }

    private static function generateToken(): string
    {
        $length = Config::get('app.csrf.token_length', 32);
        $bytes = random_bytes(max(16, (int) $length));
        return bin2hex($bytes);
    }
}
