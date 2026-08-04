<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

final class AssistantController extends BaseController
{
    public function index(): void
    {
        echo $this->view('dashboard.index', [
            'pageTitle' => 'AI Assistant',
            'user' => ['name' => 'Dr. Jordan Lee'],
        ], 'dashboard');
    }
}
