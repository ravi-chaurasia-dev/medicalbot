<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\CSRF;
use App\Core\SessionManager;

final class AuthMiddleware
{
    public static function requireAuth(): void
    {
        if (! SessionManager::has('user')) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();

        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== $role) {
            header('Location: /dashboard');
            exit;
        }
    }

    public static function guestOnly(): void
    {
        if (SessionManager::has('user')) {
            header('Location: /dashboard');
            exit;
        }
    }

    public static function verifyCsrf(): void
    {
        $token = $_POST['_csrf_token'] ?? null;

        if (! CSRF::validate($token)) {
            http_response_code(419);
            SessionManager::set('error', 'Security token expired or invalid.');
            header('Location: /login');
            exit;
        }
    }
}
