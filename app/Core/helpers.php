<?php

declare(strict_types=1);

use App\Core\Environment;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return Environment::get($key, $default);
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        return rtrim(dirname(__DIR__, 2), DIRECTORY_SEPARATOR) . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return base_path('public' . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return base_path('storage' . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return rtrim(env('APP_URL', 'http://localhost:8000'), '/') . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return rtrim(env('APP_URL', 'http://localhost:8000'), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . url($path), true, 302);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return App\Core\Csrf::token();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('dd')) {
    function dd(mixed ...$values): never
    {
        foreach ($values as $value) {
            var_dump($value);
        }
        exit;
    }
}
