<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class App
{
    private static ?self $instance = null;

    private string $basePath;

    private array $config = [];

    public Request $request;

    public Router $router;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function setBasePath(string $basePath): void
    {
        self::getInstance()->basePath = $basePath;
    }

    public static function setConfig(array $config): void
    {
        self::getInstance()->config = $config;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    public function run(): void
    {
        if (!isset($this->request) || !isset($this->router)) {
            throw new RuntimeException('Application request and router must be configured before running.');
        }

        $this->router->dispatch($this->request);
    }

    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path !== '' ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    public function config(string $key, mixed $default = null): mixed
    {
        return Config::get($key, $default);
    }
}
