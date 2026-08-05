<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;
use App\Models\ChatMessageModel;
use App\Models\HealthMetricModel;
use App\Models\LabReportModel;
use App\Models\SymptomReportModel;

final class HealthController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $user = SessionManager::get('user', []);
        $userId = (int) ($user['id'] ?? 0);

        $healthMetricModel = new HealthMetricModel();
        $labReportModel = new LabReportModel();
        $symptomReportModel = new SymptomReportModel();
        $chatModel = new ChatMessageModel();

        $recentLabReports = $labReportModel->searchReports($userId, []);
        $recentSymptomReports = $symptomReportModel->getReportsByUserId($userId);
        $recentMetrics = $healthMetricModel->getRecentMetrics($userId, 10);
        $recentChats = $chatModel->getRecentMessagesByUserId($userId, 5);
        $latestBloodPressure = $healthMetricModel->getMetricsByUserId($userId, 'blood_pressure', 1);
        $latestWeight = $healthMetricModel->getMetricsByUserId($userId, 'weight', 1);
        $latestSugar = $healthMetricModel->getMetricsByUserId($userId, 'blood_sugar', 1);

        echo $this->view('dashboard.index', [
            'pageTitle' => 'Clinical Insights',
            'user' => $user,
            'recentLabReports' => $recentLabReports,
            'recentSymptomReports' => $recentSymptomReports,
            'recentMetrics' => $recentMetrics,
            'recentChats' => $recentChats,
            'latestBloodPressure' => $latestBloodPressure[0] ?? null,
            'latestWeight' => $latestWeight[0] ?? null,
            'latestSugar' => $latestSugar[0] ?? null,
        ], 'dashboard');
    }
}
