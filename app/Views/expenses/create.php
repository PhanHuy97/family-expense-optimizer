    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm khoản chi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/expenses'), ENT_QUOTES, 'UTF-8') ?>">Khoản chi</a></li>
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
                        <h3 class="card-title">Thông tin khoản chi</h3>
                    </div>
                    <form action="<?= htmlspecialchars($this->url('/expenses'), ENT_QUOTES, 'UTF-8') ?>" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category">Loại chi</label>
                                <select id="category" name="category" class="form-control" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option
                                            value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>"
                                            <?= ($expense['category'] ?? '') === $category ? 'selected' : '' ?>
                                        >
                                            <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Tên khoản chi</label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="form-control"
                                    value="<?= htmlspecialchars((string) ($expense['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
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
                                    value="<?= htmlspecialchars((string) ($expense['amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="expense_date">Ngày chi</label>
                                <input
                                    type="date"
                                    id="expense_date"
                                    name="expense_date"
                                    class="form-control"
                                    value="<?= htmlspecialchars((string) ($expense['expense_date'] ?? date('Y-m-d')), ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label for="note">Ghi chú</label>
                                <textarea id="note" name="note" class="form-control" rows="3"><?= htmlspecialchars((string) ($expense['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Lưu
                            </button>
                            <a href="<?= htmlspecialchars($this->url('/expenses'), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
