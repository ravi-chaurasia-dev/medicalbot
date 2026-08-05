<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\SessionManager;
use App\Middleware\AuthMiddleware;

final class AssistantController extends BaseController
{
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        echo $this->view('dashboard.index', [
            'pageTitle' => 'AI Assistant',
            'user' => SessionManager::get('user', []),
        ], 'dashboard');
    }
}
