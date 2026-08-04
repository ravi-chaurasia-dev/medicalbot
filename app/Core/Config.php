<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    private static array $config = [];

    public static function loadAll(string $path): void
    {
        $files = glob(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $data = require $file;

            if (is_array($data)) {
                self::$config[$name] = $data;
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$config;

        foreach ($segments as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    public static function all(): array
    {
        return self::$config;
    }
}
