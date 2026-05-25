<?php
$formatCurrency = static function ($amount): string {
    return number_format((float) $amount, 0, ',', '.') . ' VND';
};
$budgetClass = $isBudgetWarning ? 'danger' : 'success';
$budgetText = $isBudgetWarning ? 'Sắp vượt hạn mức' : 'Trong hạn mức';
?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= htmlspecialchars($this->url('/dashboard'), ENT_QUOTES, 'UTF-8') ?>">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?= htmlspecialchars($formatCurrency($income), ENT_QUOTES, 'UTF-8') ?></h3>
                                <p>Tổng thu</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-arrow-trend-up"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?= htmlspecialchars($formatCurrency($expense), ENT_QUOTES, 'UTF-8') ?></h3>
                                <p>Tổng chi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-cart-shopping"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?= htmlspecialchars($formatCurrency($balance), ENT_QUOTES, 'UTF-8') ?></h3>
                                <p>Số dư</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-<?= $budgetClass ?>">
                            <div class="inner">
                                <h3><?= number_format($budgetUsage, 1, ',', '.') ?>%</h3>
                                <p>Cảnh báo hạn mức</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-triangle-exclamation"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <section class="col-lg-8 connectedSortable">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-line mr-1"></i>
                                    Biểu đồ thu chi
                                </h3>
                            </div>
                            <div class="card-body">
                                <canvas id="incomeExpenseChart" height="120"></canvas>
                            </div>
                        </div>
                    </section>

                    <section class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-gauge-high mr-1"></i>
                                    Hạn mức tháng này
                                </h3>
                            </div>
                            <div class="card-body">
                                <strong><?= htmlspecialchars($budgetText, ENT_QUOTES, 'UTF-8') ?></strong>
                                <p class="text-muted mb-2">
                                    Đã chi <?= htmlspecialchars($formatCurrency($expense), ENT_QUOTES, 'UTF-8') ?>
                                    trên hạn mức <?= htmlspecialchars($formatCurrency($budgetLimit), ENT_QUOTES, 'UTF-8') ?>.
                                </p>
                                <div class="progress">
                                    <div
                                        class="progress-bar bg-<?= $budgetClass ?>"
                                        role="progressbar"
                                        style="width: <?= min($budgetUsage, 100) ?>%"
                                        aria-valuenow="<?= (int) min($budgetUsage, 100) ?>"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

    <script>
        window.dashboardChart = {
            labels: <?= json_encode($chartLabels, JSON_UNESCAPED_UNICODE) ?>,
            income: <?= json_encode($incomeSeries) ?>,
            expense: <?= json_encode($expenseSeries) ?>
        };

        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('incomeExpenseChart');

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: window.dashboardChart.labels,
                    datasets: [
                        {
                            label: 'Thu',
                            data: window.dashboardChart.income,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.12)',
                            tension: 0.35,
                            fill: true
                        },
                        {
                            label: 'Chi',
                            data: window.dashboardChart.expense,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.12)',
                            tension: 0.35,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function (value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + ' VND';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
