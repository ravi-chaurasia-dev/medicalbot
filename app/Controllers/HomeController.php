<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

final class HomeController extends BaseController
{
    public function index(): void
    {
        echo $this->view('home.index', [
            'pageTitle' => 'Welcome to MediAI',
        ], 'app');
    }
}
