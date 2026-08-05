<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\MedicalHistoryModel;
use App\Middleware\AuthMiddleware;

final class ProfileController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user');

        $profileModel = new UserProfileModel();
        $historyModel = new MedicalHistoryModel();

        echo $this->view('user.profile', [
            'pageTitle' => 'My profile',
            'user' => $user,
            'profile' => $profileModel->findByUserId((int) $user['id']) ?? [],
            'history' => $historyModel->findByUserId((int) $user['id']) ?? [],
        ], 'dashboard');
    }

    public function save(): void
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token. Please try again.', 'danger');
            $this->redirect('/profile');
        }

        $user = SessionManager::get('user');
        $userId = (int) ($user['id'] ?? 0);

        $profileData = [
            'age' => (int) ($_POST['age'] ?? 0),
            'gender' => in_array($_POST['gender'] ?? '', ['male', 'female', 'other'], true) ? (string) $_POST['gender'] : '',
            'height' => Validator::sanitize((string) ($_POST['height'] ?? '')),
            'weight' => Validator::sanitize((string) ($_POST['weight'] ?? '')),
            'blood_group' => Validator::sanitize((string) ($_POST['blood_group'] ?? '')),
            'emergency_contact' => Validator::sanitize((string) ($_POST['emergency_contact'] ?? '')),
            'address' => Validator::sanitize((string) ($_POST['address'] ?? '')),
        ];

        $historyData = [
            'diseases' => Validator::sanitize((string) ($_POST['diseases'] ?? '')),
            'surgeries' => Validator::sanitize((string) ($_POST['surgeries'] ?? '')),
            'current_medications' => Validator::sanitize((string) ($_POST['current_medications'] ?? '')),
            'allergies' => Validator::sanitize((string) ($_POST['allergies'] ?? '')),
            'vaccination_history' => Validator::sanitize((string) ($_POST['vaccination_history'] ?? '')),
            'family_medical_history' => Validator::sanitize((string) ($_POST['family_medical_history'] ?? '')),
        ];

        $errors = [];
        $name = Validator::sanitize((string) ($_POST['name'] ?? ''));

        if (! Validator::required($name) || ! Validator::minLength($name, 2)) {
            $errors[] = 'Full name is required and must be at least 2 characters.';
        }

        if ($profileData['age'] < 0 || $profileData['age'] > 120) {
            $errors[] = 'Please enter a valid age.';
        }

        if ($profileData['blood_group'] !== '' && ! Validator::maxLength($profileData['blood_group'], 10)) {
            $errors[] = 'Blood group must be 10 characters or fewer.';
        }

        if ($profileData['emergency_contact'] !== '' && ! Validator::maxLength($profileData['emergency_contact'], 50)) {
            $errors[] = 'Emergency contact must be 50 characters or fewer.';
        }

        if (! Validator::maxLength($profileData['height'], 20) || ! Validator::maxLength($profileData['weight'], 20)) {
            $errors[] = 'Height and weight must be 20 characters or fewer.';
        }

        if (! Validator::maxLength($profileData['address'], 500)) {
            $errors[] = 'Address must be 500 characters or fewer.';
        }

        if (! Validator::maxLength($historyData['diseases'], 1000) || ! Validator::maxLength($historyData['surgeries'], 1000) || ! Validator::maxLength($historyData['current_medications'], 1000) || ! Validator::maxLength($historyData['allergies'], 1000) || ! Validator::maxLength($historyData['vaccination_history'], 1000) || ! Validator::maxLength($historyData['family_medical_history'], 1000)) {
            $errors[] = 'Medical history fields must be 1000 characters or fewer.';
        }

        if ($errors !== []) {
            Flash::set('error', implode(' ', $errors), 'danger');
            $this->redirect('/profile');
        }

        $profileModel = new UserProfileModel();
        $historyModel = new MedicalHistoryModel();
        $profileModel->upsert($userId, $profileData);
        $historyModel->upsert($userId, $historyData);

        $userModel = new UserModel();
        $userModel->updateUser($userId, [
            'name' => $name,
        ]);

        SessionManager::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        Flash::set('success', 'Profile information saved successfully.', 'success');
        $this->redirect('/profile');
    }

    public function uploadPhoto(): void
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token. Please try again.', 'danger');
            $this->redirect('/profile');
        }

        $user = SessionManager::get('user');
        if (! isset($user['id'])) {
            $this->redirect('/login');
        }

        if (! isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            Flash::set('error', 'No valid photo was uploaded.', 'danger');
            $this->redirect('/profile');
        }

        $file = $_FILES['photo'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (! in_array($file['type'], $allowed, true)) {
            Flash::set('error', 'Only JPG, PNG, and WEBP files are allowed.', 'danger');
            $this->redirect('/profile');
        }

        $folder = dirname(__DIR__, 3) . '/public/uploads/profiles';
        if (! is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $filename = 'user_' . (int) $user['id'] . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $target = $folder . '/' . $filename;

        if (! move_uploaded_file($file['tmp_name'], $target)) {
            Flash::set('error', 'Photo upload failed.', 'danger');
            $this->redirect('/profile');
        }

        $profileModel = new UserProfileModel();
        $profileModel->upsert((int) $user['id'], ['photo_path' => '/uploads/profiles/' . $filename]);

        Flash::set('success', 'Profile photo uploaded.', 'success');
        $this->redirect('/profile');
    }
}
