<?php

declare(strict_types=1);

use App\Core\ErrorHandler;

ErrorHandler::register();
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->run();
