<?php

declare(strict_types=1);

use App\Core\Router;

$router = new Router();

$router->get('/', 'App\\Controllers\\HomeController@index');
$router->get('/login', 'App\\Controllers\\AuthController@loginView');
$router->post('/login', 'App\\Controllers\\AuthController@login');
$router->get('/dashboard', 'App\\Controllers\\DashboardController@index');
$router->get('/logout', 'App\\Controllers\\AuthController@logout');

return $router;
