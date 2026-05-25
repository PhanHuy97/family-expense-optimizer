<?php

declare(strict_types=1);

require __DIR__ . '/app/Core/Database.php';

$config = require __DIR__ . '/config/database.php';

try {
    $pdo = App\Core\Database::connection($config);
    $databaseName = $pdo->query('SELECT DATABASE()')->fetchColumn();

    echo 'Connected to database: ' . htmlspecialchars((string) $databaseName, ENT_QUOTES, 'UTF-8');
} catch (Throwable $exception) {
    http_response_code(500);

    echo 'Database connection failed: ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8');
}
