<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\MedicalReasoner;
use App\Core\Validator;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\SymptomReportModel;

final class SymptomCheckerController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        echo $this->view('symptom-checker.index', [
            'pageTitle' => 'Symptom Checker',
            'user' => SessionManager::get('user', []),
        ], 'dashboard');
    }

    public function submit(): void
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/symptom-checker');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/symptom-checker');
        }

        $age = (int) ($_POST['age'] ?? 0);
        $gender = (string) ($_POST['gender'] ?? '');
        $symptoms = array_filter(array_map('trim', (array) ($_POST['symptoms'] ?? [])));
        $duration = Validator::sanitize((string) ($_POST['duration'] ?? ''));
        $painLevel = (int) ($_POST['pain_level'] ?? 0);
        $temperature = (float) ($_POST['temperature'] ?? 0.0);
        $medicalHistory = Validator::sanitize((string) ($_POST['medical_history'] ?? ''));
        $currentMedicines = Validator::sanitize((string) ($_POST['current_medicines'] ?? ''));
        $smoking = in_array($_POST['smoking'] ?? 'no', ['yes', 'no'], true) ? $_POST['smoking'] : 'no';
        $alcohol = in_array($_POST['alcohol'] ?? 'no', ['yes', 'no'], true) ? $_POST['alcohol'] : 'no';
        $exercise = in_array($_POST['exercise'] ?? 'none', ['none', 'light', 'moderate', 'heavy'], true) ? $_POST['exercise'] : 'none';
        $familyHistory = Validator::sanitize((string) ($_POST['family_history'] ?? ''));

        $errors = [];

        if ($age <= 0 || $age > 120) {
            $errors[] = 'Please enter a valid age.';
        }

        if (! in_array($gender, ['male', 'female', 'other'], true)) {
            $errors[] = 'Please select a valid gender.';
        }

        if ($symptoms === []) {
            $errors[] = 'Please select at least one symptom.';
        }

        if ($duration === '') {
            $errors[] = 'Please provide the duration of symptoms.';
        }

        if ($painLevel < 0 || $painLevel > 10) {
            $errors[] = 'Pain level must be between 0 and 10.';
        }

        if ($temperature < 30 || $temperature > 45) {
            $errors[] = 'Please enter a realistic temperature in Celsius.';
        }

        if ($errors !== []) {
            Flash::set('error', implode(' ', $errors), 'danger');
            SessionManager::set('form_data', $_POST);
            $this->redirect('/symptom-checker');
        }

        $payload = [
            'age' => $age,
            'gender' => $gender,
            'symptoms' => $symptoms,
            'duration' => $duration,
            'pain_level' => $painLevel,
            'temperature' => $temperature,
            'medical_history' => $medicalHistory,
            'current_medicines' => $currentMedicines,
            'smoking' => $smoking,
            'alcohol' => $alcohol,
            'exercise' => $exercise,
            'family_history' => $familyHistory,
        ];

        $analysis = MedicalReasoner::analyze($payload);
        $reportModel = new SymptomReportModel();
        $user = SessionManager::get('user', []);
        $reportId = $reportModel->createReport([
            'user_id' => (int) ($user['id'] ?? 0),
            'age' => $age,
            'gender' => $gender,
            'symptoms' => $symptoms,
            'duration' => $duration,
            'pain_level' => $painLevel,
            'temperature' => $temperature,
            'medical_history' => $medicalHistory,
            'current_medicines' => $currentMedicines,
            'smoking' => $smoking,
            'alcohol' => $alcohol,
            'exercise' => $exercise,
            'family_history' => $familyHistory,
            'conditions' => $analysis['conditions'],
            'risk_level' => $analysis['risk_level'],
            'emergency_warning' => $analysis['emergency_warning'],
            'explanation' => $analysis['explanation'],
            'suggested_tests' => $analysis['suggested_tests'],
            'confidence' => $analysis['confidence'],
            'follow_up_questions' => $analysis['follow_up_questions'],
        ]);

        echo $this->view('symptom-checker.result', [
            'pageTitle' => 'Symptom Checker Result',
            'user' => SessionManager::get('user', []),
            'reportId' => $reportId,
            'payload' => $payload,
            'analysis' => $analysis,
        ], 'dashboard');
    }

    public function history(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user', []);
        $reportModel = new SymptomReportModel();
        $reports = $reportModel->getReportsByUserId((int) ($user['id'] ?? 0));

        echo $this->view('symptom-checker.history', [
            'pageTitle' => 'Symptom History',
            'user' => $user,
            'reports' => $reports,
        ], 'dashboard');
    }
}
