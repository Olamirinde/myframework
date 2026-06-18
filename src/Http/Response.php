<?php

namespace App\Http;

class Response
{
    private $status;
    private $body;
    private $headers;

    public function __construct($status = 200, $body = '')
    {
        $this->status  = $status;
        $this->body    = $body;
        $this->headers = [];
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    private function sendHeaders()
    {
        http_response_code($this->status);

        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    public function send()
    {
        if (is_array($this->body)) {
            $this->setHeader('Content-Type', 'application/json');
            $this->sendHeaders();
            echo json_encode($this->body);
            return;
        }

        $this->sendHeaders();
        echo $this->body;
    }
}