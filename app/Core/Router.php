<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Router
{
    private array $routes = [];
    private ?string $currentRoute = null;

    public function __construct()
    {
        $this->routes = require dirname(__DIR__, 2) . '/config/routes.php';
    }

    public function dispatch(string $uri): void
    {
        $path = $this->normalizeUri($uri);
        $this->currentRoute = $path;

        if (array_key_exists($path, $this->routes)) {
            [$controller, $method] = $this->routes[$path];
            $this->callController($controller, $method);
            return;
        }

        if (isset($this->routes['/404'])) {
            [$controller, $method] = $this->routes['/404'];
            $this->callController($controller, $method);
            return;
        }

        http_response_code(404);
        echo 'Page not found';
    }

    private function normalizeUri(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $basePath = $this->detectBasePath();

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, strlen('/index.php'));
        }

        $path = trim($path, '/');
        return '/' . ($path === '' ? '' : $path);
    }

    private function detectBasePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = dirname($scriptName);
        $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';

        $basePath = '';

        if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($requestPath, $scriptDir)) {
            $basePath = $scriptDir;
        }

        if ($basePath === '' && $scriptName !== '' && str_starts_with($requestPath, $scriptName)) {
            $basePath = $scriptName;
        }

        if (str_ends_with($basePath, '/index.php')) {
            $basePath = dirname($basePath);
        }

        return rtrim($basePath, '/');
    }

    private function callController(string $controller, string $method): void
    {
        $class = new $controller();

        if (! method_exists($class, $method)) {
            throw new RuntimeException(sprintf('Controller method %s::%s does not exist.', $controller, $method));
        }

        $class->$method();
    }
}
