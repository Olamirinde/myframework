<?php

namespace App\Http;

class Request
{
    public $method;
    public $uri;
    public $query;
    public $body;
    public $headers;

    public function __construct($method, $uri, $query, $body, $headers = [])
    {
        $this->method  = strtoupper($method);
        $this->uri     = strtok($uri, '?') ?: '/';
        $this->query   = $query;
        $this->body    = $body;
        $this->headers = $headers;
    }

    public static function capture()
    {
        $method      = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $body        = [];

        if (str_contains($contentType, 'application/json')) {
            $raw  = file_get_contents('php://input');
            $body = json_decode($raw, true) ?? [];
        } elseif (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            parse_str(file_get_contents('php://input'), $body);
        }

        return new static(
            $method,
            $_SERVER['REQUEST_URI'] ?? '/',
            $_GET,
            $body,
            getallheaders()
        );
    }
}