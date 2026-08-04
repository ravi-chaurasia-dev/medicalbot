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
        $uri = trim(parse_url($uri, PHP_URL_PATH) ?: '/', '/');
        return '/' . ($uri === '' ? '' : $uri);
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
