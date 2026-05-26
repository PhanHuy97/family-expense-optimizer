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
$router->get('/login', [App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [App\Controllers\AuthController::class, 'login']);
$router->get('/register', [App\Controllers\AuthController::class, 'showRegister']);
$router->post('/register', [App\Controllers\AuthController::class, 'register']);
$router->post('/logout', [App\Controllers\AuthController::class, 'logout']);
$router->get('/incomes', [App\Controllers\IncomeController::class, 'index']);
$router->get('/incomes/create', [App\Controllers\IncomeController::class, 'create']);
$router->post('/incomes', [App\Controllers\IncomeController::class, 'store']);
$router->get('/incomes/edit', [App\Controllers\IncomeController::class, 'edit']);
$router->post('/incomes/update', [App\Controllers\IncomeController::class, 'update']);
$router->post('/incomes/delete', [App\Controllers\IncomeController::class, 'delete']);
$router->get('/expenses', [App\Controllers\ExpenseController::class, 'index']);
$router->get('/expenses/create', [App\Controllers\ExpenseController::class, 'create']);
$router->post('/expenses', [App\Controllers\ExpenseController::class, 'store']);
$router->get('/expenses/edit', [App\Controllers\ExpenseController::class, 'edit']);
$router->post('/expenses/update', [App\Controllers\ExpenseController::class, 'update']);
$router->post('/expenses/delete', [App\Controllers\ExpenseController::class, 'delete']);
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/');
