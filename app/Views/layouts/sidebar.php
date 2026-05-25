<?php
$appName = $this->config['app_name'] ?? 'Family Expense Optimizer';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = rtrim((string) ($this->config['base_path'] ?? ''), '/');

if ($basePath !== '' && str_starts_with($requestPath, $basePath)) {
    $requestPath = substr($requestPath, strlen($basePath)) ?: '/';
}

$requestPath = '/' . trim($requestPath, '/');
$requestPath = $requestPath === '/' ? '/' : rtrim($requestPath, '/');

$isActive = static function (array $paths) use ($requestPath): string {
    foreach ($paths as $path) {
        if ($path === '/') {
            if ($requestPath === '/') {
                return ' active';
            }

            continue;
        }

        if ($requestPath === $path || str_starts_with($requestPath, rtrim($path, '/') . '/')) {
            return ' active';
        }
    }

    return '';
};

$menuItems = [
    [
        'label' => 'Dashboard',
        'url' => '/dashboard',
        'icon' => 'fas fa-chart-pie',
        'activePaths' => ['/', '/dashboard'],
    ],
    [
        'label' => 'Khoản thu',
        'url' => '/incomes',
        'icon' => 'fas fa-arrow-trend-up',
        'activePaths' => ['/incomes'],
    ],
    [
        'label' => 'Khoản chi',
        'url' => '/expenses',
        'icon' => 'fas fa-cart-shopping',
        'activePaths' => ['/expenses'],
    ],
    [
        'label' => 'Thiết lập tài chính',
        'url' => '/settings',
        'icon' => 'fas fa-sliders',
        'activePaths' => ['/settings'],
    ],
    [
        'label' => 'Kế hoạch mua sắm',
        'url' => '/purchase-plans',
        'icon' => 'fas fa-calendar-check',
        'activePaths' => ['/purchase-plans'],
    ],
];
?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="brand-link">
            <i class="brand-image fas fa-wallet ml-3 mt-2"></i>
            <span class="brand-text font-weight-light"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-header">QUẢN LÝ TÀI CHÍNH</li>

                    <?php foreach ($menuItems as $item): ?>
                        <li class="nav-item">
                            <a
                                href="<?= htmlspecialchars($this->url($item['url']), ENT_QUOTES, 'UTF-8') ?>"
                                class="nav-link<?= $isActive($item['activePaths']) ?>"
                            >
                                <i class="nav-icon <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                                <p><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </aside>
