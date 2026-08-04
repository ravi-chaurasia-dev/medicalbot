<?php

declare(strict_types=1);

namespace App\Core;

use ErrorException;
use Throwable;

final class ErrorHandler
{
    public static function register(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException(Throwable $exception): void
    {
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();

        Logger::error('Unhandled exception: ' . $message, [
            'file' => $file,
            'line' => $line,
        ]);

        if (Config::get('app.debug', false)) {
            echo '<pre>' . htmlspecialchars($message . PHP_EOL . $file . ':' . $line, ENT_QUOTES, 'UTF-8') . '</pre>';
            exit(1);
        }

        http_response_code(500);
        echo '<h1>Something went wrong.</h1>';
        exit(1);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
            Logger::error('Fatal error: ' . $error['message'], [
                'file' => $error['file'],
                'line' => $error['line'],
            ]);
        }
    }
}
