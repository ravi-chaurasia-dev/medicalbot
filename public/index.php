<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

use App\Core\ErrorHandler;

ErrorHandler::register();
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->run();
