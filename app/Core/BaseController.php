<?php

declare(strict_types=1);

namespace App\Core;

abstract class BaseController
{
    protected function view(string $template, array $data = [], ?string $layout = 'layouts.app'): string
    {
        $viewRenderer = new ViewRenderer();

        return $viewRenderer->render($template, $data, $layout);
    }

    protected function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }
}
