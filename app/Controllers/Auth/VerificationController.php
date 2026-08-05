<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Core\BaseController;
use App\Core\Flash;
use App\Models\UserModel;

final class VerificationController extends BaseController
{
    public function verifyEmail(): void
    {
        $token = $_GET['token'] ?? '';

        if ($token === '') {
            Flash::set('error', 'Missing email verification token.', 'danger');
            $this->redirect('/login');
        }

        $verification = $this->db()->prepare('SELECT * FROM email_verifications WHERE token = :token AND expires_at > NOW() LIMIT 1');
        $verification->execute(['token' => $token]);
        $record = $verification->fetch();

        if ($record === false) {
            Flash::set('error', 'The email verification link is invalid or expired.', 'danger');
            $this->redirect('/login');
        }

        $userModel = new UserModel();
        $userModel->verifyEmail((int) $record['user_id']);
        $this->db()->prepare('DELETE FROM email_verifications WHERE token = :token')->execute(['token' => $token]);

        Flash::set('success', 'Your email has been verified successfully. You may now log in.', 'success');
        $this->redirect('/login');
    }
}
