<?php

declare(strict_types=1);

namespace App\Core;

final class RateLimiter
{
    private const STORAGE = __DIR__ . '/../../storage/rate_limit.json';

    public static function checkRequest(?string $key = null, int $max = 100, int $windowSeconds = 60): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $identifier = $key ?? $ip;

        $data = [];
        if (is_file(self::STORAGE)) {
            $content = @file_get_contents(self::STORAGE);
            $data = $content ? json_decode($content, true) : [];
            if (! is_array($data)) {
                $data = [];
            }
        }

        $now = time();
        $windowStart = $now - $windowSeconds;

        // purge old entries
        foreach ($data as $id => $entries) {
            $data[$id] = array_filter($entries, static fn($t) => $t >= $windowStart);
            if ($data[$id] === []) {
                unset($data[$id]);
            }
        }

        $entries = $data[$identifier] ?? [];
        $entries[] = $now;
        $data[$identifier] = $entries;

        // persist
        @file_put_contents(self::STORAGE, json_encode($data, JSON_UNESCAPED_SLASHES));

        if (count($entries) > $max) {
            http_response_code(429);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Too many requests. Please slow down.';
            exit;
        }
    }
}
