<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Session;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        if (!Session::has('user_id')) {
            redirect('/login');
        }

        echo $this->view('dashboard.index', [
            'title' => 'Dashboard | MediAI',
            'userName' => Session::get('user_name', 'Clinician'),
        ], 'layouts.dashboard');
    }
}
