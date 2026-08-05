<?php

declare(strict_types=1);

namespace App\Core;

final class LabOCRService
{
    public function extractTextFromFile(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($extension, ['png', 'jpg', 'jpeg'], true)) {
            return $this->extractTextFromImage($filePath);
        }

        if ($extension === 'pdf') {
            return $this->extractTextFromPdf($filePath);
        }

        return '';
    }

    private function extractTextFromImage(string $filePath): string
    {
        if ($this->isExecutableAvailable('tesseract')) {
            $command = sprintf('tesseract %s stdout -l eng 2>/dev/null', escapeshellarg($filePath));
            $text = shell_exec($command);
            return trim((string) $text);
        }

        return '';
    }

    private function extractTextFromPdf(string $filePath): string
    {
        if ($this->isExecutableAvailable('pdftotext')) {
            $command = sprintf('pdftotext -layout %s - 2>/dev/null', escapeshellarg($filePath));
            $text = shell_exec($command);
            return trim((string) $text);
        }

        if (extension_loaded('imagick') && $this->isExecutableAvailable('tesseract')) {
            $imagick = new \Imagick();
            $imagick->setResolution(200, 200);
            $imagick->readImage($filePath);
            $text = '';

            foreach ($imagick as $page) {
                $page->setImageFormat('png');
                $pagePath = sys_get_temp_dir() . '/labreport_page_' . uniqid() . '.png';
                file_put_contents($pagePath, $page);
                $text .= (string) shell_exec(sprintf('tesseract %s stdout -l eng 2>/dev/null', escapeshellarg($pagePath)));
                @unlink($pagePath);
            }

            return trim($text);
        }

        return '';
    }

    private function isExecutableAvailable(string $name): bool
    {
        $which = stripos(PHP_OS_FAMILY, 'Windows') === 0 ? 'where' : 'command -v';
        $output = shell_exec(sprintf('%s %s 2>/dev/null', $which, escapeshellarg($name)));
        return $output !== null && trim($output) !== '';
    }
}
