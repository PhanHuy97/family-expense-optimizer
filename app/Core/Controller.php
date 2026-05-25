<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    protected function view(string $view, array $data = []): void
    {
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
}
