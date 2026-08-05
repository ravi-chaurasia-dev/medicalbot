<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\BaseController;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\UserModel;
use App\Models\HospitalModel;
use App\Models\LabReportModel;
use App\Models\SymptomReportModel;
use App\Models\ChatMessageModel;
use App\Models\AuditLogModel;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user', []);
        if (($user['role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }

        $userModel = new UserModel();
        $hospitalModel = new HospitalModel();
        $labReportModel = new LabReportModel();
        $symptomReportModel = new SymptomReportModel();
        $chatModel = new ChatMessageModel();
        $auditModel = new AuditLogModel();

        $totalUsers = count($userModel->all());
        $totalHospitals = count($hospitalModel->all());
        $totalLabReports = count($labReportModel->all());
        $totalSymptomReports = count($symptomReportModel->all());
        $totalChats = count($chatModel->getRecentMessages(1000));
        $recentLogs = $auditModel->getRecentLogs(10);

        echo $this->view('admin.dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'user' => $user,
            'metrics' => [
                'users' => $totalUsers,
                'hospitals' => $totalHospitals,
                'lab_reports' => $totalLabReports,
                'symptom_reports' => $totalSymptomReports,
                'chats' => $totalChats,
            ],
            'recentLogs' => $recentLogs,
        ], 'dashboard');
    }
}
