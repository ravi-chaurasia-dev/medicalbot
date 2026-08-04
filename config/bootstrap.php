<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Config;
use App\Core\Environment;
use App\Core\ErrorHandler;
use App\Core\Logger;
use App\Core\Session;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$basePath = dirname(__DIR__);
Environment::load($basePath);
Config::load($basePath . '/config');
Session::start();
ErrorHandler::register();
Logger::info('Application bootstrap complete.');

App::setBasePath($basePath);
App::setConfig(Config::all());

return App::getInstance();
