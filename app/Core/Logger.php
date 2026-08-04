<?php

declare(strict_types=1);

namespace App\Core;

use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    private string $logPath;

    public function __construct(?string $logPath = null)
    {
        $this->logPath = $logPath ?? Config::get('app.logging.path', storage_path('logs/app.log'));
        $directory = dirname($this->logPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
    }

    public static function info(string $message, array $context = []): void
    {
        $logger = new self();
        $logger->info($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        $logger = new self();
        $logger->error($message, $context);
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->write('EMERGENCY', $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->write('ALERT', $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->write('CRITICAL', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->write('WARNING', $message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        $this->write('NOTICE', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->write('INFO', $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->write('DEBUG', $message, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->write(strtoupper((string) $level), (string) $message, $context);
    }

    public function write(string $level, string $message, array $context = []): void
    {
        $timestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $contextString = $context === [] ? '' : ' ' . json_encode($context, JSON_THROW_ON_ERROR);
        $entry = sprintf('[%s] %s %s%s' . PHP_EOL, $timestamp, $level, $message, $contextString);

        file_put_contents($this->logPath, $entry, FILE_APPEND | LOCK_EX);
    }
}
