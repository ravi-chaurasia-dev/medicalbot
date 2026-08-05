<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\LabOCRService;
use App\Core\LabReportAnalyzer;
use App\Core\ImageOptimizer;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\LabReportModel;
use App\Models\LabReportValueModel;

final class LabReportController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user', []);
        $filters = [
            'search' => (string) ($_GET['search'] ?? ''),
            'status' => (string) ($_GET['status'] ?? ''),
            'start_date' => (string) ($_GET['start_date'] ?? ''),
            'end_date' => (string) ($_GET['end_date'] ?? ''),
        ];

        $reportModel = new LabReportModel();
        $reports = $reportModel->searchReports((int) ($user['id'] ?? 0), $filters);

        echo $this->view('lab-reports.index', [
            'pageTitle' => 'Lab Reports',
            'user' => $user,
            'reports' => $reports,
            'filters' => $filters,
        ], 'dashboard');
    }

    public function upload(): void
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lab-reports');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/lab-reports');
        }

        $user = SessionManager::get('user', []);
        $file = $_FILES['lab_report'] ?? null;

        if (! $file || $file['error'] !== UPLOAD_ERR_OK) {
            Flash::set('error', 'Please upload a valid lab report file.', 'danger');
            $this->redirect('/lab-reports');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'png', 'jpg', 'jpeg'];

        if (! in_array($extension, $allowed, true)) {
            Flash::set('error', 'Only PDF, PNG, JPG, and JPEG files are supported.', 'danger');
            $this->redirect('/lab-reports');
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            Flash::set('error', 'File size must be under 10MB.', 'danger');
            $this->redirect('/lab-reports');
        }

        $folder = dirname(__DIR__, 2) . '/public/uploads/lab-reports';
        if (! is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $filename = 'lab_report_' . (int) ($user['id'] ?? 0) . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
        $path = $folder . '/' . $filename;

        // verify MIME type using finfo to avoid spoofed extensions
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowedMime = [
            'application/pdf',
            'image/png',
            'image/jpeg',
        ];

        if (! in_array($mime, $allowedMime, true)) {
            Flash::set('error', 'Uploaded file type is not permitted.', 'danger');
            $this->redirect('/lab-reports');
        }

        if (! move_uploaded_file($file['tmp_name'], $path)) {
            Flash::set('error', 'Unable to save the uploaded file.', 'danger');
            $this->redirect('/lab-reports');
        }

        // optimize images to save space
        if (in_array($mime, ['image/png', 'image/jpeg'], true)) {
            ImageOptimizer::optimize($path);
        }

        $ocrService = new LabOCRService();
        $extracted = $ocrService->extractTextFromFile($path);

        $analyzer = new LabReportAnalyzer();
        $analysis = $analyzer->analyze($extracted);

        $reportModel = new LabReportModel();
        $reportId = $reportModel->create([
            'user_id' => (int) ($user['id'] ?? 0),
            'original_file_name' => $file['name'],
            'stored_file_path' => '/uploads/lab-reports/' . $filename,
            'file_type' => $extension,
            'file_size' => (int) $file['size'],
            'raw_text' => $extracted,
            'report_summary' => $analysis['summary'],
            'explanation' => $analysis['explanation'],
            'recommendations' => implode('; ', $analysis['recommendations']),
            'risk_level' => $analysis['risk_level'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // audit log
        \App\Core\Audit::record((int) ($user['id'] ?? 0), 'lab_report.upload', 'Uploaded a lab report', ['report_id' => $reportId, 'file' => $file['name']]);

        $valueModel = new LabReportValueModel();
        $valueModel->addValues($reportId, $analysis['values']);

        Flash::set('success', 'Lab report uploaded and analyzed successfully.', 'success');
        $this->redirect('/lab-reports/view?id=' . $reportId);
    }

    public function view(): void
    {
        AuthMiddleware::requireAuth();

        $reportId = (int) ($_GET['id'] ?? 0);
        $user = SessionManager::get('user', []);

        $reportModel = new LabReportModel();
        $report = $reportModel->findById($reportId);

        if ($report === null || (int) $report['user_id'] !== (int) ($user['id'] ?? 0)) {
            Flash::set('error', 'Report not found.', 'danger');
            $this->redirect('/lab-reports');
        }

        $valueModel = new LabReportValueModel();
        $values = $valueModel->getValuesByReportId($reportId);

        echo $this->view('lab-reports.view', [
            'pageTitle' => 'Lab Report Details',
            'user' => $user,
            'report' => $report,
            'values' => $values,
        ], 'dashboard');
    }

    public function download(): void
    {
        AuthMiddleware::requireAuth();

        $reportId = (int) ($_GET['id'] ?? 0);
        $user = SessionManager::get('user', []);

        $reportModel = new LabReportModel();
        $report = $reportModel->findById($reportId);

        if ($report === null || (int) $report['user_id'] !== (int) ($user['id'] ?? 0)) {
            Flash::set('error', 'Report not found.', 'danger');
            $this->redirect('/lab-reports');
        }

        $path = dirname(__DIR__, 2) . '/public' . $report['stored_file_path'];
        if (! is_file($path)) {
            Flash::set('error', 'Report file is missing.', 'danger');
            $this->redirect('/lab-reports');
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($report['original_file_name']) . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function delete(): void
    {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/lab-reports');
        }

        if (! CSRF::validate($_POST['_csrf_token'] ?? null)) {
            Flash::set('error', 'Invalid security token.', 'danger');
            $this->redirect('/lab-reports');
        }

        $reportId = (int) ($_POST['id'] ?? 0);
        $user = SessionManager::get('user', []);

        $reportModel = new LabReportModel();
        $report = $reportModel->findById($reportId);

        if ($report === null || (int) $report['user_id'] !== (int) ($user['id'] ?? 0)) {
            Flash::set('error', 'Report not found.', 'danger');
            $this->redirect('/lab-reports');
        }

        $path = dirname(__DIR__, 2) . '/public' . $report['stored_file_path'];
        if (is_file($path)) {
            @unlink($path);
        }

        $reportModel->deleteReport($reportId);
        Flash::set('success', 'Lab report deleted successfully.', 'success');
        $this->redirect('/lab-reports');
    }

    public function compare(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user', []);
        $leftId = (int) ($_GET['left_id'] ?? 0);
        $rightId = (int) ($_GET['right_id'] ?? 0);

        $reportModel = new LabReportModel();
        $leftReport = $reportModel->findById($leftId);
        $rightReport = $reportModel->findById($rightId);

        if ($leftReport === null || $rightReport === null || (int) $leftReport['user_id'] !== (int) ($user['id'] ?? 0) || (int) $rightReport['user_id'] !== (int) ($user['id'] ?? 0)) {
            Flash::set('error', 'Unable to compare selected reports.', 'danger');
            $this->redirect('/lab-reports');
        }

        $valueModel = new LabReportValueModel();
        $leftValues = $valueModel->getValuesByReportId($leftId);
        $rightValues = $valueModel->getValuesByReportId($rightId);

        echo $this->view('lab-reports.compare', [
            'pageTitle' => 'Compare Lab Reports',
            'user' => $user,
            'leftReport' => $leftReport,
            'rightReport' => $rightReport,
            'leftValues' => $leftValues,
            'rightValues' => $rightValues,
        ], 'dashboard');
    }
}
