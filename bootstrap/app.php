<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Router;
use App\Core\Config;
use App\Core\Database;
use App\Core\Logger;
use App\Core\SessionManager;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

if (! isset($_ENV['APP_ENV'])) {
    $_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = 'local';
}

Config::loadAll(dirname(__DIR__) . '/config');

$timezone = Config::get('app.timezone', 'UTC');
date_default_timezone_set($timezone);

SessionManager::start();
Logger::initialize();

// Apply global security headers
\App\Core\Security::apply();

// Basic rate limiting
\App\Core\RateLimiter::checkRequest();

if (! SessionManager::has('user')) {
    $rememberMeController = new \App\Controllers\Auth\RememberMeController();
    $rememberMeController->restore();
}

$app = new App(new Router());
return $app;
