<?php
class Router {
    private $routes = [];

    public function addRoute($path, $handler) {
        $this->routes[$path] = $handler;
    }

    public function handleRequest() {
        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);

        if (array_key_exists($path, $this->routes)) {
            require $this->routes[$path];
        } else {
            $this->notFound();
        }
    }

    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        require '404.php';
    }
}
?>