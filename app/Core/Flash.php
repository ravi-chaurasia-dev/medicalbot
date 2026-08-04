<?php

declare(strict_types=1);

namespace App\Core;

final class Flash
{
    public static function add(string $type, string $message): void
    {
        $flash = Session::get('flash', []);
        $flash[] = ['type' => $type, 'message' => $message];
        Session::put('flash', $flash);
    }

    public static function get(): array
    {
        $flash = Session::get('flash', []);
        Session::forget('flash');

        return $flash;
    }

    public static function success(string $message): void
    {
        self::add('success', $message);
    }

    public static function error(string $message): void
    {
        self::add('danger', $message);
    }

    public static function warning(string $message): void
    {
        self::add('warning', $message);
    }

    public static function info(string $message): void
    {
        self::add('info', $message);
    }
}
