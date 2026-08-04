<?php

declare(strict_types=1);

namespace App\Core;

use ErrorException;
use Throwable;

final class ErrorHandler
{
    public static function register(): void
    {
        error_reporting(E_ALL);
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (! (error_reporting() & $severity)) {
            return false;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException(Throwable $exception): void
    {
        Logger::getInstance()->error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);

        if (Config::get('app.debug', false)) {
            http_response_code(500);
            echo '<pre>' . htmlspecialchars($exception->__toString(), ENT_QUOTES, 'UTF-8') . '</pre>';
            return;
        }

        http_response_code(500);
        echo 'An unexpected error occurred. Please try again later.';
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error === null) {
            return;
        }

        if (in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
            Logger::getInstance()->critical($error['message'], [
                'file' => $error['file'],
                'line' => $error['line'],
            ]);
        }
    }
}
