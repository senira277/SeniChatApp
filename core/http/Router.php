<?php

class Router
{
    private $routes = []; // To store routes by HTTP method
    private $middleware = []; // To store middleware functions
    private $subRouters = []; // To store sub-routers

    // Convenience method for GET requests
    public function get(string $path, $handler)
    {
        $this->add_handler($path, "GET", $handler);
    }

    // Convenience method for POST requests
    public function post(string $path, $handler)
    {
        $this->add_handler($path, "POST", $handler);
    }

    // Register a route for any HTTP method
    public function add_handler(string $path, string $method, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

    // Register middleware
    public function useMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    // Route incoming requests
    public function handle($req, $res)
    {
        // Execute middleware
        foreach ($this->middleware as $mw) {
            $mw($req, $res);
        }

        // Handle the route logic
        $this->handleRequest($req, $res);
    }

    private function handleRequest($req, $res)
    {
        $method = $req->method;
        $path = $req->path;

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            call_user_func($handler, $req, $res);
        } else {
            $this->handleSubRouters($req, $res);
        }
    }

    private function handleSubRouters($req, $res)
    {
        foreach ($this->subRouters as $prefix => $router) {
            if (strpos($req->path, $prefix) === 0) {
                // Update request path to remove prefix and delegate to the sub-router
                $req->path = substr($req->path, strlen($prefix));
                $router->handle($req, $res);
                return;
            }
        }

        // Default: Return 404 if no handler is found
        $res->status(404)->send(["error" => "Not Found"]);
    }

    // Use a sub-router for a specific base path
    public function use($prefix, $router)
    {
        $this->subRouters[$prefix] = $router;
    }
}
