<?php

use App\Controllers\UserController;
use App\Http\Response;
use App\Middleware\AuthMiddleware;


$router->addRoute('GET', '/users', [UserController::class, 'index']);
$router->addRoute('GET', '/user/{id}', [UserController::class, 'show']);

$router->addRoute('POST', '/user', [UserController::class, 'store'], [AuthMiddleware::class]);
$router->addRoute('PUT', '/user/{id}', [UserController::class, 'update'], [AuthMiddleware::class]);
$router->addRoute('DELETE', '/user/{id}', [UserController::class, 'delete'], [AuthMiddleware::class]);