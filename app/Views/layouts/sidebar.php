<?php
$appName = $this->config['app_name'] ?? 'Family Expense Optimizer';
?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="brand-link">
            <i class="brand-image fas fa-wallet ml-3 mt-2"></i>
            <span class="brand-text font-weight-light"><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link active">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-header">QUẢN LÝ TÀI CHÍNH</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-arrow-trend-up"></i>
                            <p>Khoản thu</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cart-shopping"></i>
                            <p>Khoản chi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-triangle-exclamation"></i>
                            <p>Hạn mức</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
