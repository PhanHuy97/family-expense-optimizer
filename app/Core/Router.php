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

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(string $requestUri): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = $this->normalizePath(parse_url($requestUri, PHP_URL_PATH) ?: '/');
        $basePath = $this->normalizePath($this->config['base_path'] ?? '');

        if ($basePath !== '/' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath)) ?: '/';
            $path = $this->normalizePath($path);
        }

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
