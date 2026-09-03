<?php

class App {
    protected $controller = "HomeController";
    protected $method = "index";
    protected $params = [];

    protected $handlers = [];
    protected $middlewareMap = [
        "auth" => AuthMiddleware::class,
        "guest" => GuestMiddleware::class,
        "role" => RoleMiddleware::class,
    ];

    private const DEFAULT_GET = "GET";
    private const DEFAULT_POST = "POST";
    
    public function setDefaultConstroller($controller) {
        $this->controller = $controller;
    }

    public function setDefaultMethod($method) {
        $this->method = $method;
    }

    public function get($uri, $callback, $middleware = []) {
        $this->setHandler(self::DEFAULT_GET, $uri, $callback, $middleware);
    }

    public function post($uri, $callback, $middleware = []) {
        $this->setHandler(self::DEFAULT_POST, $uri, $callback, $middleware);     
    }

    public function setHandler(string $method, string $path, $handler, $middleware) {
        $this->handlers[$method . $path] = [
            "method" => $method,
            "path"=> $path,
            "handler"=> $handler,
            "middleware"=> $middleware
        ];
    }

    public function run() {
        $routeFound = false;
        $url = $this->parseURL();
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri = "/" . implode("/", $url);
        
        foreach ($this->handlers as $handler) {
            if ($requestMethod !== $handler['method']) continue;

            $route = preg_replace('/\{[a-zA-Z]+\}/', '([^\/]+)', $handler['path']);
            $route = "#^" . $route . "$#";

            if (preg_match($route, $uri, $matches)) {
                array_shift($matches);

                foreach ($handler['middleware'] as $mw) {
                    $parts = explode(":", $mw);
                    $name = $parts[0];
                    $param = $parts[1] ?? null;

                    if (!isset($this->middlewareMap[$name])) {
                        throw new Exception("Middleware $name tidak terdaftar");
                    }

                    $middlewareClass = $this->middlewareMap[$name];

                    if ($param) {
                        (new $middlewareClass)->handle($param);
                    } else {
                        (new $middlewareClass)->handle();
                    }
                }

                require_once __DIR__ . "/../controllers/" . $handler['handler'][0] . ".php";
                $controller = new $handler['handler'][0];
                $method = $handler['handler'][1];

                call_user_func_array([$controller, $method], $matches);
                return;
            }
        }

        if (!$routeFound) {
            $this->call404();
            return;
        }
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url']);
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode("/", $url);
        }
        return [];
    }

    private function call404() {
        require_once "../app/controllers/ErrorController.php";
        $error = new ErrorController();
        $error->notFound();
    }

}









?>