<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\SessionManager;
use App\Models\UserModel;

final class RememberMeController extends BaseController
{
    public function restore(): void
    {
        $token = $_COOKIE['remember_me'] ?? '';

        if ($token === '') {
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findByRememberToken($token);

        if ($user === null) {
            return;
        }

        SessionManager::set('user', [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);
    }
}
