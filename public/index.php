<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Http\Request;
use App\Http\Router;
use App\Middleware\LogMiddleware;
use App\Middleware\AuthMiddleware;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$request = Request::capture();
$router  = new Router();

$router->addMiddleware(LogMiddleware::class);

require_once dirname(__DIR__) . '/routes/route.php';

$router->dispatch($request);