<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\UserModel;

final class UserController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $userModel = new UserModel();
        $users = $userModel->all('created_at DESC');

        echo $this->view('admin.users', [
            'pageTitle' => 'User Management',
            'user' => $user,
            'users' => $users,
        ], 'dashboard');
    }
}
