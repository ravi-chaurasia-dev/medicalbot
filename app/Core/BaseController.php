<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class BaseController
{
    protected ?PDO $db = null;

    public function __construct()
    {
        // Lazy load database connection only when needed.
    }

    protected function db(): PDO
    {
        return $this->db ??= Database::getConnection();
    }

    protected function view(string $template, array $data = [], ?string $layout = 'app'): string
    {
        $view = new ViewRenderer(dirname(__DIR__, 2) . '/resources/views');
        return $view->render($template, $data, $layout);
    }

    protected function redirect(string $path, int $status = 302): never
    {
        header('Location: ' . $path, true, $status);
        exit;
    }

    protected function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
