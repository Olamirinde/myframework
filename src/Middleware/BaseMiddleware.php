<?php

namespace App\Middleware;

interface BaseMiddleware
{
    public function handle($request, $next);
}