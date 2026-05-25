<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private $routes = [];
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(string $requestUri): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = $this->normalizePath(parse_url($requestUri, PHP_URL_PATH) ?: '/');
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '404 - Page not found';
            return;
        }

        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass($this->config);
        $controller->{$action}();
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
