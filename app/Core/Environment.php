<?php

declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;

final class Environment
{
    public static function load(string $basePath): void
    {
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->safeLoad();

        date_default_timezone_set(self::get('APP_TIMEZONE', 'UTC'));
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, $_ENV) || array_key_exists($key, $_SERVER);
    }
}
