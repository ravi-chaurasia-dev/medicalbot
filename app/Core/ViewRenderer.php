<?php

declare(strict_types=1);

namespace App\Core;

final class ViewRenderer
{
    private string $viewsPath;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, DIRECTORY_SEPARATOR);
    }

    public function render(string $template, array $data = [], ?string $layout = 'app'): string
    {
        $templateFile = $this->viewsPath . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $template) . '.php';

        if (! is_file($templateFile)) {
            throw new \RuntimeException(sprintf('View file not found: %s', $templateFile));
        }

        extract($data, EXTR_SKIP);
        $content = $this->capture($templateFile, $data);

        if ($layout === null) {
            return $content;
        }

        $layoutFile = $this->viewsPath . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';

        if (! is_file($layoutFile)) {
            throw new \RuntimeException(sprintf('Layout file not found: %s', $layoutFile));
        }

        $viewContent = $content;
        extract(['content' => $viewContent, 'pageTitle' => $data['pageTitle'] ?? 'MediAI']);

        ob_start();
        require $layoutFile;
        return (string) ob_get_clean();
    }

    private function capture(string $templateFile, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $templateFile;
        return (string) ob_get_clean();
    }
}
