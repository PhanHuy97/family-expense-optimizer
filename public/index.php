<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = ROOT_PATH . '/app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

$config = require ROOT_PATH . '/config/app.php';
$config['database'] = require ROOT_PATH . '/config/database.php';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = $scriptDir === '/' ? '' : rtrim($scriptDir, '/');

if (str_ends_with($basePath, '/public')) {
    $basePath = substr($basePath, 0, -7);
}

$config['base_path'] = $basePath === '/' ? '' : $basePath;

$router = new App\Core\Router($config);
$router->get('/', [App\Controllers\DashboardController::class, 'index']);
$router->get('/dashboard', [App\Controllers\DashboardController::class, 'index']);
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/');
