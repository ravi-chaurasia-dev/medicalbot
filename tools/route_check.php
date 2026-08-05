<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$routes = require __DIR__ . '/../config/routes.php';

foreach ($routes as $path => $handler) {
    [$controller, $method] = $handler;
    if (! class_exists($controller)) {
        echo "Missing controller for route {$path}: {$controller}\n";
        continue;
    }
    $rc = new ReflectionClass($controller);
    if (! $rc->hasMethod($method)) {
        echo "Missing method {$method} on controller {$controller} for route {$path}\n";
    }
}

echo "Route check complete.\n";
