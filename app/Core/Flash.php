<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    public static function set(string $key, string $message, string $type = 'info'): void
    {
        $flashes = SessionManager::get('flash_messages', []);
        $flashes[] = [
            'key' => $key,
            'type' => $type,
            'message' => $message,
        ];

        SessionManager::set('flash_messages', $flashes);
    }

    public static function get(string $key = null): array
    {
        $messages = SessionManager::get('flash_messages', []);

        if ($key !== null) {
            $messages = array_values(array_filter($messages, static fn (array $item): bool => $item['key'] === $key));
        }

        if ($messages !== []) {
            SessionManager::remove('flash_messages');
        }

        return $messages;
    }

    public static function has(string $key = null): bool
    {
        return self::get($key) !== [];
    }
}
