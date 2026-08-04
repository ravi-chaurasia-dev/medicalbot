<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use RuntimeException;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable|string $handler): void
    {
        $this->routes['GET'][$this->normalizePath($path)] = $this->resolveHandler($handler);
    }

    public function post(string $path, callable|string $handler): void
    {
        $this->routes['POST'][$this->normalizePath($path)] = $this->resolveHandler($handler);
    }

    public function put(string $path, callable|string $handler): void
    {
        $this->routes['PUT'][$this->normalizePath($path)] = $this->resolveHandler($handler);
    }

    public function patch(string $path, callable|string $handler): void
    {
        $this->routes['PATCH'][$this->normalizePath($path)] = $this->resolveHandler($handler);
    }

    public function delete(string $path, callable|string $handler): void
    {
        $this->routes['DELETE'][$this->normalizePath($path)] = $this->resolveHandler($handler);
    }

    public function middleware(array $middlewares): self
    {
        $this->routes['_middleware'][] = $middlewares;

        return $this;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $this->normalizePath($request->uri());
        $route = $this->routes[$method][$uri] ?? null;

        if ($route === null) {
            http_response_code(404);
            echo '404 - Not Found';
            return;
        }

        if (is_callable($route)) {
            $route($request);
            return;
        }

        [$controllerName, $methodName] = explode('@', $route);
        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            throw new RuntimeException("Controller method {$controllerName}::{$methodName} not found.");
        }

        $controller->{$methodName}($request);
    }

    private function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }

    private function resolveHandler(callable|string $handler): callable|string
    {
        if (is_string($handler)) {
            return $handler;
        }

        return $handler;
    }
}
