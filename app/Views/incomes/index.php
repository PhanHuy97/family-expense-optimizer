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
                        <h1>Khoản thu</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Khoản thu</li>
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
                        <h3 class="card-title">Danh sách khoản thu</h3>
                        <div class="card-tools">
                            <a href="<?= htmlspecialchars($this->url('/incomes/create'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus mr-1"></i>
                                Thêm khoản thu
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Tên khoản thu</th>
                                    <th style="width: 180px;">Số tiền</th>
                                    <th style="width: 150px;">Ngày thu</th>
                                    <th>Ghi chú</th>
                                    <th style="width: 150px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($incomes)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Chưa có khoản thu nào.</td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($incomes as $index => $income): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars((string) $income['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($formatCurrency($income['amount']), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime((string) $income['income_date'])), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars((string) ($income['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>
                                            <a
                                                href="<?= htmlspecialchars($this->url('/incomes/edit?id=' . (int) $income['id']), ENT_QUOTES, 'UTF-8') ?>"
                                                class="btn btn-warning btn-sm"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form
                                                action="<?= htmlspecialchars($this->url('/incomes/delete'), ENT_QUOTES, 'UTF-8') ?>"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa khoản thu này?');"
                                            >
                                                <input type="hidden" name="id" value="<?= (int) $income['id'] ?>">
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
