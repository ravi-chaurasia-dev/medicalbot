<?php

declare(strict_types=1);

namespace App\Controllers\User;

use App\Core\BaseController;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\MedicalHistoryModel;

final class ProfileController extends BaseController
{
    public function index(): void
    {
        $user = SessionManager::get('user');

        if (! isset($user['id'])) {
            $this->redirect('/login');
        }

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
        $user = SessionManager::get('user');

        if (! isset($user['id'])) {
            $this->redirect('/login');
        }

        $userId = (int) $user['id'];

        $profileData = [
            'age' => (int) ($_POST['age'] ?? 0),
            'gender' => (string) ($_POST['gender'] ?? ''),
            'height' => (string) ($_POST['height'] ?? ''),
            'weight' => (string) ($_POST['weight'] ?? ''),
            'blood_group' => (string) ($_POST['blood_group'] ?? ''),
            'emergency_contact' => (string) ($_POST['emergency_contact'] ?? ''),
            'address' => (string) ($_POST['address'] ?? ''),
            'photo_path' => (string) ($_POST['photo_path'] ?? ''),
        ];

        $historyData = [
            'diseases' => (string) ($_POST['diseases'] ?? ''),
            'surgeries' => (string) ($_POST['surgeries'] ?? ''),
            'current_medications' => (string) ($_POST['current_medications'] ?? ''),
            'allergies' => (string) ($_POST['allergies'] ?? ''),
            'vaccination_history' => (string) ($_POST['vaccination_history'] ?? ''),
            'family_medical_history' => (string) ($_POST['family_medical_history'] ?? ''),
        ];

        $profileModel = new UserProfileModel();
        $historyModel = new MedicalHistoryModel();
        $profileModel->upsert($userId, $profileData);
        $historyModel->upsert($userId, $historyData);

        $userModel = new UserModel();
        $userModel->updateUser($userId, [
            'name' => Validator::sanitize((string) ($_POST['name'] ?? $user['name'])),
        ]);

        SessionManager::set('user', [
            'id' => $userId,
            'name' => Validator::sanitize((string) ($_POST['name'] ?? $user['name'])),
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        Flash::set('success', 'Profile information saved successfully.', 'success');
        $this->redirect('/profile');
    }

    public function uploadPhoto(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
