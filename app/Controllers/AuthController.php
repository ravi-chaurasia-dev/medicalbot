<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Request;
use App\Core\Session;

final class AuthController extends BaseController
{
    public function loginView(): void
    {
        echo $this->view('auth.login', [
            'title' => 'Login | MediAI',
        ], 'layouts.auth');
    }

    public function login(Request $request): void
    {
        try {
            Csrf::verify();
        } catch (\Throwable $e) {
            Flash::error('Invalid or expired session token.');
            redirect('/login');
        }

        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        if ($email === '' || $password === '') {
            Flash::error('Email and password are required.');
            redirect('/login');
        }

        Session::put('user_id', 1);
        Session::put('user_name', 'Demo Clinician');
        Flash::success('Welcome back to MediAI.');

        redirect('/dashboard');
    }

    public function logout(): void
    {
        Session::destroy();
        redirect('/login');
    }
}
