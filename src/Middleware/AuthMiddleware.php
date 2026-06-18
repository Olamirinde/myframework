<?php

namespace App\Middleware;

use App\Middleware\BaseMiddleware;
use App\Http\Response;

class AuthMiddleware implements BaseMiddleware
{
    public function handle($request, $next)
    {
        $token = $request->headers['Authorization'] ?? null;

        if (!$token) {
            return new Response(401, ['message' => 'Unauthorized']);
        }

        return $next($request);
    }
}