<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\Mailer;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Models\UserModel;

final class RegisterController extends BaseController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        echo $this->view('auth.register', [
            'pageTitle' => 'Create account',
        ], 'auth');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            $message = 'Invalid security token. Please try again.';
            Flash::set('error', $message, 'danger');
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'message' => $message]);
            }
            $this->redirect('/register');
        }

        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['password_confirmation'] ?? '');
        $role = in_array($_POST['role'] ?? 'patient', ['patient', 'admin'], true) ? $_POST['role'] : 'patient';

        $errors = [];

        if (! Validator::required($name) || ! Validator::minLength($name, 2)) {
            $errors['name'] = 'Full name is required and must be at least 2 characters.';
        }

        if (! Validator::email($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (! Validator::password($password)) {
            $errors['password'] = 'Password must be at least 8 characters long and contain at least one uppercase letter and one number.';
        }

        if ($password !== $confirmPassword) {
            $errors['password_confirmation'] = 'Passwords do not match.';
        }

        $userModel = new UserModel();
        if ($userModel->findByEmail($email) !== null) {
            $errors['email'] = 'An account with this email already exists.';
        }

        if ($errors !== []) {
            SessionManager::set('form_errors', $errors);
            SessionManager::set('form_data', [
                'name' => $name,
                'email' => $email,
                'role' => $role,
            ]);
            if ($this->isAjaxRequest()) {
                $this->json(['success' => false, 'errors' => $errors]);
            }
            $this->redirect('/register');
        }

        $userId = $userModel->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);

        $token = bin2hex(random_bytes(32));
        $this->db->prepare('INSERT INTO email_verifications (user_id, token, expires_at, created_at) VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 1 DAY), NOW())')->execute([
            'user_id' => $userId,
            'token' => $token,
        ]);

        $verificationUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/verify-email?token=' . $token;
        $mailer = new Mailer();
        $mailer->send(
            $email,
            'Verify your MediAI account',
            '<h3>Welcome to MediAI</h3><p>Hi ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>' .
            '<p>Please verify your email address by clicking below:</p>' .
            '<p><a href="' . htmlspecialchars($verificationUrl, ENT_QUOTES, 'UTF-8') . '">Verify Email</a></p>'
        );

        $message = 'Your account was created successfully. Please check your email to verify your account.';
        Flash::set('success', $message, 'success');

        if ($this->isAjaxRequest()) {
            $this->json(['success' => true, 'redirect' => '/login', 'message' => $message]);
        }

        $this->redirect('/login');
    }

    private function isAjaxRequest(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }
}
