<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;

final class ErrorController extends BaseController
{
    public function notFound(): void
    {
        http_response_code(404);
        echo $this->view('errors.404', [
            'pageTitle' => 'Page Not Found',
        ], 'app');
    }
}
