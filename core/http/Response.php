<?php

class Response
{
    private $statusCode = 200;
    private $headers = [];
    private $body = [];

    // Set HTTP status code
    public function status($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    // Send JSON response
    public function send($body)
    {
        $this->body = $body;
        header('Content-Type: application/json');
        http_response_code($this->statusCode);
        echo json_encode($this->body);
    }
}
