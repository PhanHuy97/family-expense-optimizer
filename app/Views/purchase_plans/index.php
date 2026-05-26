<?php
$formatCurrency = static function ($amount): string {
    return number_format((float) $amount, 0, ',', '.') . ' VND';
};

$formatMonth = static function (?string $date): string {
    if ($date === null || $date === '') {
        return '-';
    }

    return date('m/Y', strtotime($date));
};

$statusMap = [
    'can_buy' => ['class' => 'success', 'label' => 'Có thể mua'],
    'delayed' => ['class' => 'warning', 'label' => 'Nên dời tháng'],
    'not_found' => ['class' => 'secondary', 'label' => 'Chưa tìm được'],
];
?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Kế hoạch mua sắm</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Kế hoạch mua sắm</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <?php foreach ($flashMessages as $message): ?>
                    <div class="alert alert-<?= htmlspecialchars((string) $message['type'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars((string) $message['message'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endforeach; ?>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách kế hoạch</h3>
                        <div class="card-tools">
                            <a href="<?= htmlspecialchars($this->url('/purchase-plans/create'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Thêm kế hoạch
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Món muốn mua</th>
                                    <th style="width: 180px;">Số tiền</th>
                                    <th style="width: 150px;">Tháng mong muốn</th>
                                    <th style="width: 150px;">Tháng đề xuất</th>
                                    <th style="width: 150px;">Trạng thái</th>
                                    <th>Ghi chú</th>
                                    <th style="width: 90px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($plans)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Chưa có kế hoạch mua sắm nào.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($plans as $index => $plan): ?>
                                    <?php $status = $statusMap[$plan['status']] ?? ['class' => 'secondary', 'label' => $plan['status']]; ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars((string) $plan['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($formatCurrency($plan['amount']), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($formatMonth((string) $plan['desired_month']), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($formatMonth($plan['suggested_month'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <span class="badge badge-<?= htmlspecialchars($status['class'], ENT_QUOTES, 'UTF-8') ?>">
                                                <?= htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars((string) ($plan['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <form
                                                action="<?= htmlspecialchars($this->url('/purchase-plans/delete'), ENT_QUOTES, 'UTF-8') ?>"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa kế hoạch này?');"
                                            >
                                                <input type="hidden" name="id" value="<?= (int) $plan['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
