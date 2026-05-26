    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm kế hoạch mua sắm</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/purchase-plans'), ENT_QUOTES, 'UTF-8') ?>">Kế hoạch mua sắm</a></li>
                            <li class="breadcrumb-item active">Thêm</li>
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

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin kế hoạch</h3>
                    </div>
                    <form action="<?= htmlspecialchars($this->url('/purchase-plans'), ENT_QUOTES, 'UTF-8') ?>" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Món muốn mua</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="form-control"
                                    value="<?= htmlspecialchars((string) ($plan['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="amount">Số tiền</label>
                                <input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    class="form-control"
                                    min="0"
                                    step="1000"
                                    value="<?= htmlspecialchars((string) ($plan['amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="desired_month">Tháng mong muốn</label>
                                <input
                                    type="month"
                                    id="desired_month"
                                    name="desired_month"
                                    class="form-control"
                                    value="<?= htmlspecialchars((string) ($plan['desired_month'] ?? date('Y-m')), ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="note">Ghi chú</label>
                                <textarea id="note" name="note" class="form-control" rows="3"><?= htmlspecialchars((string) ($plan['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Lưu kế hoạch
                            </button>
                            <a href="<?= htmlspecialchars($this->url('/purchase-plans'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
