<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        if (!Session::has('csrf_token')) {
            Session::put('csrf_token', bin2hex(random_bytes(32)));
        }

        return (string) Session::get('csrf_token');
    }

    public static function validate(mixed $token): bool
    {
        return hash_equals(self::token(), (string) $token);
    }

    public static function verify(): void
    {
        $request = App::getInstance()->request;
        $token = $request->input('_csrf');

        if (!$token || !self::validate($token)) {
            http_response_code(419);
            throw new \RuntimeException('CSRF token validation failed.');
        }
    }
}
