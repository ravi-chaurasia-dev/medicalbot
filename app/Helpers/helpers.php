<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\SessionManager;

if (! function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        return match (strtolower((string) $value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value,
        };
    }
}

if (! function_exists('detect_base_path')) {
    function detect_base_path(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

        if ($scriptName !== '') {
            $scriptDir = dirname($scriptName);
            if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($requestUri, $scriptDir)) {
                return rtrim($scriptDir, '/');
            }

            if (str_starts_with($requestUri, $scriptName)) {
                $basePath = $scriptName;
                if (str_ends_with($basePath, '/index.php')) {
                    $basePath = dirname($basePath);
                }

                return rtrim($basePath, '/');
            }
        }

        return '';
    }
}

if (! function_exists('base_url')) {
    function base_url(): string
    {
        $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $basePath = detect_base_path();
        return $scheme . '://' . $host . ($basePath !== '' ? $basePath : '');
    }
}

if (! function_exists('asset')) {
    function asset(string $path): string
    {
        return rtrim(base_url(), '/') . '/assets/' . ltrim($path, '/');
    }
}

if (! function_exists('url')) {
    function url(string $path = '/'): string
    {
        $base = rtrim(base_url(), '/');
        $normalizedPath = '/' . ltrim($path, '/');
        return $base . $normalizedPath;
    }
}

if (! function_exists('dd')) {
    function dd(mixed ...$vars): never
    {
        foreach ($vars as $var) {
            echo '<pre>' . htmlspecialchars(print_r($var, true), ENT_QUOTES, 'UTF-8') . '</pre>';
        }
        exit(1);
    }
}

if (! function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return \App\Core\CSRF::token();
    }
}

if (! function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return \App\Core\CSRF::field();
    }
}

if (! function_exists('flash')) {
    function flash(string $key = 'default'): array
    {
        return \App\Core\Flash::get($key);
    }
}

if (! function_exists('session')) {
    function session(string $key, mixed $default = null): mixed
    {
        return SessionManager::get($key, $default);
    }
}

if (! function_exists('redirect')) {
    function redirect(string $path, int $status = 302): never
    {
        header('Location: ' . $path, true, $status);
        exit;
    }
}
