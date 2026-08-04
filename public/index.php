<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';

use App\Core\App;
use App\Core\Request;
use App\Core\Router;

$app = App::getInstance();
$app->setRequest(Request::fromGlobals());
$app->setRouter(require dirname(__DIR__) . '/routes/web.php');
$app->run();
