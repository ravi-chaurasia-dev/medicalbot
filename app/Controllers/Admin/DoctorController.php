<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Core\Validator;
use App\Middleware\AuthMiddleware;
use App\Models\DoctorModel;
use App\Models\HospitalModel;

final class DoctorController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $doctorModel = new DoctorModel();
        $doctors = $doctorModel->searchDoctors([]);
        $hospitalModel = new HospitalModel();
        $hospitals = $hospitalModel->all('name ASC');

        echo $this->view('admin.doctors.index', [
            'pageTitle' => 'Doctor Management',
            'user' => $user,
            'doctors' => $doctors,
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

        $hospitalModel = new HospitalModel();
        $hospitals = $hospitalModel->all('name ASC');

        echo $this->view('admin.doctors.form', [
            'pageTitle' => 'Add Doctor',
            'user' => $user,
            'doctor' => null,
            'hospitals' => $hospitals,
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
        $doctorModel = new DoctorModel();
        $doctor = $doctorModel->findById($id);

        if ($doctor === null) {
            Flash::set('error', 'Doctor not found.', 'danger');
            $this->redirect('/admin/doctors');
        }

        $hospitalModel = new HospitalModel();
        $hospitals = $hospitalModel->all('name ASC');

        echo $this->view('admin.doctors.form', [
            'pageTitle' => 'Edit Doctor',
            'user' => $user,
            'doctor' => $doctor,
            'hospitals' => $hospitals,
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
            $this->redirect('/admin/doctors');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/admin/doctors');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $data = [
            'hospital_id' => (int) ($_POST['hospital_id'] ?? 0),
            'first_name' => Validator::sanitize((string) ($_POST['first_name'] ?? '')),
            'last_name' => Validator::sanitize((string) ($_POST['last_name'] ?? '')),
            'specialty' => Validator::sanitize((string) ($_POST['specialty'] ?? '')),
            'phone' => Validator::sanitize((string) ($_POST['phone'] ?? '')),
            'email' => Validator::sanitize((string) ($_POST['email'] ?? '')),
            'website' => Validator::sanitize((string) ($_POST['website'] ?? '')),
            'availability' => Validator::sanitize((string) ($_POST['availability'] ?? '')),
            'profile' => Validator::sanitize((string) ($_POST['profile'] ?? '')),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $doctorModel = new DoctorModel();
        if ($id === 0) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $doctorModel->create($data);
            Flash::set('success', 'Doctor added successfully.', 'success');
        } else {
            $doctorModel->update($id, $data);
            Flash::set('success', 'Doctor updated successfully.', 'success');
        }

        $this->redirect('/admin/doctors');
    }

    public function delete(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/doctors');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/admin/doctors');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $doctorModel = new DoctorModel();
        $doctorModel->delete($id);

        Flash::set('success', 'Doctor removed successfully.', 'success');
        $this->redirect('/admin/doctors');
    }
}
