<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

final class HealthController extends BaseController
{
    public function index(): void
    {
        echo $this->view('dashboard.index', [
            'pageTitle' => 'Clinical Insights',
            'user' => ['name' => 'Dr. Jordan Lee'],
        ], 'dashboard');
    }
}
