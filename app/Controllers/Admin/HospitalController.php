<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Middleware\AuthMiddleware;
use App\Models\HospitalModel;

final class HospitalController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $hospitalModel = new HospitalModel();
        $hospitals = $hospitalModel->all('created_at DESC');

        echo $this->view('admin.hospitals.index', [
            'pageTitle' => 'Hospital Management',
            'user' => $user,
            'hospitals' => $hospitals,
        ], 'dashboard');
    }

    public function create(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        echo $this->view('admin.hospitals.form', [
            'pageTitle' => 'Add Hospital',
            'user' => $user,
            'hospital' => null,
        ], 'dashboard');
    }

    public function edit(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $id = (int) ($_GET['id'] ?? 0);
        $hospitalModel = new HospitalModel();
        $hospital = $hospitalModel->findById($id);

        if ($hospital === null) {
            Flash::set('error', 'Hospital not found.', 'danger');
            $this->redirect('/admin/hospitals');
        }

        echo $this->view('admin.hospitals.form', [
            'pageTitle' => 'Edit Hospital',
            'user' => $user,
            'hospital' => $hospital,
        ], 'dashboard');
    }

    public function save(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/hospitals');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/admin/hospitals');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'name' => Validator::sanitize((string) ($_POST['name'] ?? '')),
            'address' => Validator::sanitize((string) ($_POST['address'] ?? '')),
            'latitude' => (float) ($_POST['latitude'] ?? 0.0),
            'longitude' => (float) ($_POST['longitude'] ?? 0.0),
            'phone' => Validator::sanitize((string) ($_POST['phone'] ?? '')),
            'website' => Validator::sanitize((string) ($_POST['website'] ?? '')),
            'departments' => Validator::sanitize((string) ($_POST['departments'] ?? '')),
            'rating' => max(0.0, min(5.0, (float) ($_POST['rating'] ?? 0.0))),
            'emergency_available' => isset($_POST['emergency_available']) ? '1' : '0',
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($id === 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $hospitalModel = new HospitalModel();
            $hospitalModel->create($data);
            Flash::set('success', 'Hospital added successfully.', 'success');
        } else {
            $hospitalModel = new HospitalModel();
            $hospitalModel->updateHospital($id, $data);
            Flash::set('success', 'Hospital updated successfully.', 'success');
        }

        $this->redirect('/admin/hospitals');
    }

    public function delete(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/hospitals');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/admin/hospitals');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $hospitalModel = new HospitalModel();
        $hospitalModel->deleteHospital($id);

        Flash::set('success', 'Hospital removed successfully.', 'success');
        $this->redirect('/admin/hospitals');
    }
}
