<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Middleware\AuthMiddleware;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        echo $this->view('dashboard.index', [
            'pageTitle' => 'Dashboard',
            'user' => ['name' => 'Dr. Jordan Lee'],
        ], 'dashboard');
    }
}
