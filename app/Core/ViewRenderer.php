<?php

declare(strict_types=1);

namespace App\Core;

final class ViewRenderer
{
    public function render(string $template, array $data = [], ?string $layout = null): string
    {
        $templatePath = base_path('resources/views/' . $template . '.php');
        $layoutPath = $layout !== null ? base_path('resources/views/' . $layout . '.php') : null;

        if (!is_file($templatePath)) {
            throw new \RuntimeException("View not found: {$template}");
        }

        extract($data, EXTR_SKIP);
        ob_start();

        if ($layoutPath !== null && is_file($layoutPath)) {
            require $layoutPath;
            $content = ob_get_clean();

            if ($content === false) {
                $content = '';
            }

            return $content;
        }

        require $templatePath;
        $content = ob_get_clean();

        return $content === false ? '' : $content;
    }
}
