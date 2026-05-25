<?php
$appName = $this->config['app_name'] ?? 'Family Expense Optimizer';
$pageTitle = isset($title) ? "{$title} | {$appName}" : $appName;
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($this->url('/assets/css/app.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="<?= htmlspecialchars($this->url('/register'), ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?>
        </a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Tạo tài khoản mới</p>

            <?php foreach ($flashMessages as $message): ?>
                <div class="alert alert-<?= htmlspecialchars((string) $message['type'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars((string) $message['message'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endforeach; ?>

            <form action="<?= htmlspecialchars($this->url('/register'), ENT_QUOTES, 'UTF-8') ?>" method="post">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Họ tên"
                        value="<?= htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Email"
                        value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu"
                        required
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
            </form>

            <p class="mb-0 mt-3">
                <a href="<?= htmlspecialchars($this->url('/login'), ENT_QUOTES, 'UTF-8') ?>">
                    Đã có tài khoản? Đăng nhập
                </a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
