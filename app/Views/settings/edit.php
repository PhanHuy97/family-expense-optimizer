    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thiết lập tài chính</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Thiết lập tài chính</li>
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
                        <h3 class="card-title">Thông tin thiết lập</h3>
                    </div>

                    <form action="<?= htmlspecialchars($this->url('/settings'), ENT_QUOTES, 'UTF-8') ?>" method="post">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="monthly_budget">Hạn mức chi tiêu tháng</label>
                                        <input
                                            type="number"
                                            id="monthly_budget"
                                            name="monthly_budget"
                                            class="form-control"
                                            min="0"
                                            step="1000"
                                            value="<?= htmlspecialchars((string) ($setting['monthly_budget'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="minimum_balance">Số dư tối thiểu</label>
                                        <input
                                            type="number"
                                            id="minimum_balance"
                                            name="minimum_balance"
                                            class="form-control"
                                            min="0"
                                            step="1000"
                                            value="<?= htmlspecialchars((string) ($setting['minimum_balance'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expected_monthly_income">Thu nhập dự kiến mỗi tháng</label>
                                        <input
                                            type="number"
                                            id="expected_monthly_income"
                                            name="expected_monthly_income"
                                            class="form-control"
                                            min="0"
                                            step="1000"
                                            value="<?= htmlspecialchars((string) ($setting['expected_monthly_income'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expected_monthly_expense">Chi tiêu dự kiến mỗi tháng</label>
                                        <input
                                            type="number"
                                            id="expected_monthly_expense"
                                            name="expected_monthly_expense"
                                            class="form-control"
                                            min="0"
                                            step="1000"
                                            value="<?= htmlspecialchars((string) ($setting['expected_monthly_expense'] ?? 0), ENT_QUOTES, 'UTF-8') ?>"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Lưu thiết lập
                            </button>
                            <a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary">Quay lại Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
