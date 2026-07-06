<?php

use App\Http\Response;
use App\Controllers\CategoryController;
use App\Middleware\AuthMiddleware;

$router->addRoute('GET', '/', function($request) {
    return new Response(200, ['message' => 'Inventory Service']);
});

$router->addRoute('GET',  '/categories',    [CategoryController::class, 'index']);
$router->addRoute('GET',  '/category/{id}', [CategoryController::class, 'show']);
$router->addRoute('POST', '/category',      [CategoryController::class, 'store'],  [AuthMiddleware::class]);
$router->addRoute('PUT',  '/category/{id}', [CategoryController::class, 'update'], [AuthMiddleware::class]);
