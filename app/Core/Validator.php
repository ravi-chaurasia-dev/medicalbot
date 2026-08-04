<?php

declare(strict_types=1);

namespace App\Core;

final class Validator
{
    public static function sanitize(string $value): string
    {
        return trim(strip_tags($value));
    }

    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function password(string $password): bool
    {
        return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[0-9]/', $password);
    }

    public static function required(mixed $value): bool
    {
        if (is_string($value)) {
            return trim($value) !== '';
        }

        return $value !== null && $value !== '';
    }

    public static function minLength(string $value, int $length): bool
    {
        return mb_strlen(trim($value)) >= $length;
    }

    public static function maxLength(string $value, int $length): bool
    {
        return mb_strlen(trim($value)) <= $length;
    }

    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    public static function boolValue(mixed $value): bool
    {
        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }
}
