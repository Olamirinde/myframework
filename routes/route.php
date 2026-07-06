<?php

use App\Http\Response;
use App\Controllers\CategoryController;
use App\Controllers\ProductController;
use App\Middleware\AuthMiddleware;

// Category routes
$router->addRoute('GET',  '/categories',    [CategoryController::class, 'index']);
$router->addRoute('GET',  '/category/{id}', [CategoryController::class, 'show']);
$router->addRoute('POST', '/category',  [CategoryController::class, 'store'],  [AuthMiddleware::class]);
$router->addRoute('PUT',  '/category/{id}', [CategoryController::class, 'update'], [AuthMiddleware::class]);

// Products routes
$router->addRoute('GET',  '/products',  [ProductController::class, 'index']);
$router->addRoute('GET',  '/product/{id}',  [ProductController::class, 'show']);
$router->addRoute('POST', '/product',   [ProductController::class, 'store'],    [AuthMiddleware::class]);
$router->addRoute('PUT',  '/product/{id}',  [ProductController::class, 'update'],   [AuthMiddleware::class]);
$router->addRoute('GET',  '/product/{id}/categories',   [ProductController::class, 'getCategories']);
$router->addRoute('PUT',  '/product/{id}/categories',   [ProductController::class, 'syncCategories'], [AuthMiddleware::class]);


// Stock routes
$router->addRoute('PUT', '/product/{id}/restock',   [ProductController::class, 'restock'],  [AuthMiddleware::class]);
$router->addRoute('PUT', '/product/{id}/deduct',    [ProductController::class, 'deduct'],   [AuthMiddleware::class]);
$router->addRoute('GET', '/product/{id}/movements', [ProductController::class, 'movements']);