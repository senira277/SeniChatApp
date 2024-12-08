<?php

class Request
{
    public $method;
    public $path;
    public $headers;
    public $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
        $this->body = json_decode(file_get_contents('php://input'), true);
    }
}
