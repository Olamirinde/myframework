<?php

namespace App\Http;

use App\Exceptions\ThrowException;

class Router
{
    private $routes     = [];
    private $middleware = [];

    public function addRoute($method, $uri, $handler, $middleware = [])
    {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'handler'    => $handler,
            'middleware' => $middleware,
        ];
    }


    public function addMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    private function compilePattern($routeUri)
    {
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $routeUri);
        return '#^' . $pattern . '$#';
    }

    private function match($routeUri, $requestUri)
    {
        $pattern = $this->compilePattern($routeUri);

        if (!preg_match($pattern, $requestUri, $matches)) {
            return null;
        }

        array_shift($matches);

        return $matches;
    }

    private function resolve($handler, $params)
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = new $class();
            $response = call_user_func_array([$instance, $method], $params);
        } else {
            $response = call_user_func_array($handler, $params);
        }

        return $response;
    }

    private function runMiddleware($request, $middleware, $final)
    {
        if (empty($middleware)) {
            return $final($request);
        }

        $current  = array_shift($middleware);
        $instance = new $current();

        return $instance->handle($request, function($request) use ($middleware, $final) {
            return $this->runMiddleware($request, $middleware, $final);
        });
    }

    public function dispatch($request)
    {
        try {
            foreach ($this->routes as $route) {
                if ($route['method'] !== $request->method) {
                    continue;
                }

                $params = $this->match($route['uri'], $request->uri);

                if ($params !== null) {
                    $allMiddleware = array_merge($this->middleware, $route['middleware']);

                    $response = $this->runMiddleware(
                        $request,
                        $allMiddleware,
                        function($request) use ($route, $params) {
                            return $this->resolve($route['handler'], array_merge([$request], $params));
                        }
                    );

                    if ($response instanceof Response) {
                        $response->send();
                    }

                    return;
                }
            }

            throw ThrowException::notFound();

        } catch (ThrowException $e) {
            $body = ['message' => $e->getMessage()];

            if ($e->errors !== null) {
                $body['errors'] = $e->errors;
            }

            (new Response($e->getCode(), $body))->send();

        } catch (\Throwable $e) {
            (new Response(500, [
                'message' => 'Internal Server Error',
                'error'   => $e->getMessage(),
            ]))->send();
        }
    }
}