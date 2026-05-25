<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Controller
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function url(string $path = ''): string
    {
        $basePath = rtrim((string) ($this->config['base_path'] ?? ''), '/');
        $path = '/' . ltrim($path, '/');

        return $basePath . ($path === '/' ? '' : $path);
    }

    protected function redirect(string $path, int $statusCode = 302): void
    {
        header('Location: ' . $this->url($path), true, $statusCode);
        exit;
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->setFlash('warning', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('/login');
        }
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    protected function getFlashMessages(): array
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);

        return is_array($messages) ? $messages : [];
    }

    protected function db(): PDO
    {
        return Database::connection($this->config['database'] ?? []);
    }

    protected function view(string $view, array $data = []): void
    {
        $data['flashMessages'] = $this->getFlashMessages();

        extract($data, EXTR_SKIP);

        $viewPath = ROOT_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        require ROOT_PATH . '/app/Views/layouts/header.php';
        require ROOT_PATH . '/app/Views/layouts/sidebar.php';
        require $viewPath;
        require ROOT_PATH . '/app/Views/layouts/footer.php';
    }

    protected function authView(string $view, array $data = []): void
    {
        $data['flashMessages'] = $this->getFlashMessages();

        extract($data, EXTR_SKIP);

        $viewPath = ROOT_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($viewPath)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        require $viewPath;
    }
}
