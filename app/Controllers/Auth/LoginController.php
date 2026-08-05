<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Middleware\AuthMiddleware;
use App\Models\UserModel;

final class LoginController extends BaseController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->authenticate();
            return;
        }

        AuthMiddleware::guestOnly();

        echo $this->view('auth.login', [
            'pageTitle' => 'Login',
        ], 'auth');
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            $message = 'Invalid security token.';
            Flash::set('error', $message, 'danger');
            if (($this->isAjaxRequest() ? true : false)) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $remember = isset($_POST['remember_me']) && $_POST['remember_me'] === '1';

        if (! Validator::email($email) || ! Validator::required($password)) {
            $message = 'Invalid email or password.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user === null || ! password_verify($password, $user['password_hash'])) {
            $message = 'Invalid credentials.';
            SessionManager::set('form_data', ['email' => $email]);
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        if (empty($user['email_verified_at'])) {
            $message = 'Please verify your email before logging in.';
            Flash::set('error', $message, 'warning');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        if ($user['status'] !== 'active') {
            $message = 'Your account is not active.';
            Flash::set('error', $message, 'warning');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        SessionManager::set('user', [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $userModel->setRememberToken((int) $user['id'], $token);
            setcookie('remember_me', $token, time() + (60 * 60 * 24 * 30), '/', '', false, true);
        } else {
            setcookie('remember_me', '', time() - 3600, '/', '', false, true);
            $userModel->setRememberToken((int) $user['id'], null);
        }

        $message = 'Welcome back, ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '!';
        Flash::set('success', $message, 'success');

        if ($this->isAjaxRequest()) {
            $this->json(['success' => true, 'redirect' => '/dashboard', 'message' => $message]);
        }

        $this->redirect('/dashboard');
    }

    private function isAjaxRequest(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
