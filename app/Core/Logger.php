<?php

declare(strict_types=1);

namespace App\Core;

use DateTimeImmutable;
use Psr\Log\AbstractLogger;
use RuntimeException;

final class Logger extends AbstractLogger
{
    private static ?self $instance = null;
    private string $logPath;

    private function __construct(string $logPath)
    {
        $this->logPath = $logPath;

        $directory = dirname($this->logPath);
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException(sprintf('Unable to create log directory: %s', $directory));
        }
    }

    public static function initialize(): self
    {
        $path = Config::get('app.log.path', dirname(__DIR__, 2) . '/storage/logs/app.log');

        if (self::$instance === null) {
            self::$instance = new self($path);
        }

        return self::$instance;
    }

    public static function getInstance(): self
    {
        return self::$instance ?? self::initialize();
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->write('EMERGENCY', (string) $message, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->write('ALERT', (string) $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->write('CRITICAL', (string) $message, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->write('ERROR', (string) $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->write('WARNING', (string) $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->write('NOTICE', (string) $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->write('INFO', (string) $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->write('DEBUG', (string) $message, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->write(strtoupper((string) $level), (string) $message, $context);
    }

    private function write(string $level, string $message, array $context): void
    {
        $timestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $contextString = $context === [] ? '' : ' ' . json_encode($context, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        $line = sprintf('[%s] %s %s%s' . PHP_EOL, $timestamp, $level, $message, $contextString);

        file_put_contents($this->logPath, $line, FILE_APPEND | LOCK_EX);
    }
}
