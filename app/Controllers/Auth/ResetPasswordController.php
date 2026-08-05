<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\Validator;
use App\Models\UserModel;

final class ResetPasswordController extends BaseController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update();
            return;
        }

        $token = $_GET['token'] ?? '';
        $resetRow = $this->db()->prepare('SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW() LIMIT 1');
        $resetRow->execute(['token' => $token]);
        $record = $resetRow->fetch();

        if ($record === false) {
            Flash::set('error', 'This password reset link is invalid or expired.', 'danger');
            $this->redirect('/login');
        }

        echo $this->view('auth.reset-password', [
            'pageTitle' => 'Reset password',
            'token' => $token,
        ], 'auth');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            $message = 'Invalid security token.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        $token = (string) ($_POST['token'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['password_confirmation'] ?? '');

        if (! Validator::password($password) || $password !== $confirmPassword) {
            $message = 'Password must be at least 8 characters and include uppercase and numbers, and both fields must match.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/reset-password?token=' . urlencode($token));
        }

        $resetStmt = $this->db()->prepare('SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW() LIMIT 1');
        $resetStmt->execute(['token' => $token]);
        $reset = $resetStmt->fetch();

        if ($reset === false) {
            $message = 'This password reset link is invalid or expired.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/login');
        }

        $userModel = new UserModel();
        $userModel->setPassword((int) $reset['user_id'], $password);
        $this->db()->prepare('DELETE FROM password_resets WHERE token = :token')->execute(['token' => $token]);

        $message = 'Your password has been reset successfully.';
        Flash::set('success', $message, 'success');

        if ($this->isAjaxRequest()) {
            $this->json(['success' => true, 'message' => $message, 'redirect' => '/login']);
        }

        $this->redirect('/login');
    }

    private function isAjaxRequest(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
