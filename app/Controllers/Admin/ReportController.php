<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\CSRF;
use App\Core\Flash;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\LabReportModel;
use App\Models\SymptomReportModel;

final class ReportController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $labReportModel = new LabReportModel();
        $symptomReportModel = new SymptomReportModel();

        $labReports = $labReportModel->all('created_at DESC');
        $symptomReports = $symptomReportModel->all('created_at DESC');

        echo $this->view('admin.reports.index', [
            'pageTitle' => 'Report Management',
            'user' => $user,
            'labReports' => $labReports,
            'symptomReports' => $symptomReports,
        ], 'dashboard');
    }

    public function exportCsv(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $labReportModel = new LabReportModel();
        $labReports = $labReportModel->all('created_at DESC');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="lab_reports.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'User ID', 'File Name', 'Risk Level', 'Created At']);

        foreach ($labReports as $report) {
            fputcsv($output, [
                $report['id'],
                $report['user_id'],
                $report['original_file_name'],
                $report['risk_level'],
                $report['created_at'],
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportPdf(): void
    {
        AuthMiddleware::requireAuth();
        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $labReportModel = new LabReportModel();
        $labReports = $labReportModel->all('created_at DESC');

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="lab_reports.pdf"');

        echo "<html><body><h1>Lab Reports</h1><table border=1 cellpadding=5 cellspacing=0><thead><tr><th>ID</th><th>User ID</th><th>File Name</th><th>Risk Level</th><th>Created At</th></tr></thead><tbody>";

        foreach ($labReports as $report) {
            echo sprintf(
                '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                htmlspecialchars((string) $report['id'], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string) $report['user_id'], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string) $report['original_file_name'], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string) $report['risk_level'], ENT_QUOTES, 'UTF-8'),
                htmlspecialchars((string) $report['created_at'], ENT_QUOTES, 'UTF-8')
            );
        }

        echo '</tbody></table></body></html>';
        exit;
    }
}
