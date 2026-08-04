<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    public function __construct(
        public readonly array $get = [],
        public readonly array $post = [],
        public readonly array $server = [],
        public readonly array $cookies = []
    ) {
    }

    public static function fromGlobals(): self
    {
        return new self(
            $_GET ?? [],
            $_POST ?? [],
            $_SERVER ?? [],
            $_COOKIE ?? []
        );
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string
    {
        return parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->post)) {
            return $this->post[$key];
        }

        if (array_key_exists($key, $this->get)) {
            return $this->get[$key];
        }

        return $default;
    }

    public function isAjax(): bool
    {
        return isset($this->server['HTTP_X_REQUESTED_WITH'])
            && strtolower((string) $this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
