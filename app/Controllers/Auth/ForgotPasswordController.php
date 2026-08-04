<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\Mailer;
use App\Core\Validator;
use App\Models\UserModel;

final class ForgotPasswordController extends BaseController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->sendResetLink();
            return;
        }

        echo $this->view('auth.forgot-password', [
            'pageTitle' => 'Forgot password',
        ], 'auth');
    }

    public function sendResetLink(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            $message = 'Invalid security token.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/forgot-password');
        }

        $email = strtolower(trim((string) ($_POST['email'] ?? '')));

        if (! Validator::email($email)) {
            $message = 'Please provide a valid email address.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/forgot-password');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user !== null) {
            $token = bin2hex(random_bytes(32));
            $this->db->prepare('INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR), NOW()) ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at), created_at = NOW()')->execute([
                'user_id' => (int) $user['id'],
                'token' => $token,
            ]);

            $resetLink = 'http://' . $_SERVER['HTTP_HOST'] . '/reset-password?token=' . $token;
            $mailer = new Mailer();
            $mailer->send(
                $email,
                'Reset your MediAI password',
                '<p>Hello ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . ',</p>' .
                '<p>Click the link below to reset your password:</p>' .
                '<p><a href="' . htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') . '">Reset Password</a></p>'
            );
        }

        $message = 'If an account exists for this email, a password reset link has been sent.';
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
