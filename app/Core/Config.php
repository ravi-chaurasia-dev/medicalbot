<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    private static array $config = [];

    public static function load(string $configPath): void
    {
        $files = glob($configPath . '/*.php');

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if (basename($file) === 'bootstrap.php') {
                continue;
            }

            $name = pathinfo($file, PATHINFO_FILENAME);
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
            if (!is_array($value) || !array_key_exists($segment, $value)) {
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
