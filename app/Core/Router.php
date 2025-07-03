<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $callback): void
    {
        $this->routes['GET'][$uri] = $callback;
    }

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $callback = $this->routes[$method][$uri] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo "Página no encontrada";
            return;
        }

        if (is_array($callback)) {
            [$class, $method] = $callback;
            (new $class)->$method();
        } else {
            call_user_func($callback);
        }
    }
}
