<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Models\UserModel;

final class LogoutController extends BaseController
{
    public function logout(): void
    {
        $user = SessionManager::get('user');

        if (isset($user['id'])) {
            $userModel = new UserModel();
            $userModel->setRememberToken((int) $user['id'], null);
        }

        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        }

        SessionManager::destroy();
        Flash::set('success', 'You have been logged out.', 'success');
        $this->redirect('/login');
    }
}
