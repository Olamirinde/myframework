<?php

namespace App\Middleware;

use App\Middleware\BaseMiddleware;

class LogMiddleware implements BaseMiddleware
{
    public function handle($request, $next)
    {
        error_log('[' . date('Y-m-d H:i:s') . '] ' . $request->method . ' ' . $request->uri);
        return $next($request);
    }
}