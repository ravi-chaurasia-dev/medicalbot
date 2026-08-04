<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Session;

final class AuthMiddleware
{
    public function handle(callable $next): mixed
    {
        if (!Session::has('user_id')) {
            redirect('/login');
        }

        return $next();
    }
}
