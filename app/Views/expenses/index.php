<?php
$formatCurrency = static function ($amount): string {
    return number_format((float) $amount, 0, ',', '.') . ' VND';
};
?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Khoản chi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Khoản chi</li>
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
                        <h3 class="card-title">Danh sách khoản chi</h3>
                        <div class="card-tools">
                            <a href="<?= htmlspecialchars($this->url('/expenses/create'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Thêm khoản chi
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th style="width: 150px;">Loại chi</th>
                                    <th>Tên khoản chi</th>
                                    <th style="width: 180px;">Số tiền</th>
                                    <th style="width: 150px;">Ngày chi</th>
                                    <th>Ghi chú</th>
                                    <th style="width: 150px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($expenses)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Chưa có khoản chi nào.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($expenses as $index => $expense): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars((string) $expense['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) $expense['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($formatCurrency($expense['amount']), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime((string) $expense['expense_date'])), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($expense['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <a
                                                href="<?= htmlspecialchars($this->url('/expenses/edit?id=' . (int) $expense['id']), ENT_QUOTES, 'UTF-8') ?>"
                                                class="btn btn-warning btn-sm"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form
                                                action="<?= htmlspecialchars($this->url('/expenses/delete'), ENT_QUOTES, 'UTF-8') ?>"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa khoản chi này?');"
                                            >
                                                <input type="hidden" name="id" value="<?= (int) $expense['id'] ?>">
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
