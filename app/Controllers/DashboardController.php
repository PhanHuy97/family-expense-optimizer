<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $income = 38500000;
        $expense = 29200000;
        $balance = $income - $expense;
        $budgetLimit = 30000000;
        $budgetUsage = $expense / $budgetLimit * 100;

        $this->view('dashboard.index', [
            'title' => 'Dashboard',
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
            'budgetLimit' => $budgetLimit,
            'budgetUsage' => $budgetUsage,
            'isBudgetWarning' => $budgetUsage >= 90,
            'chartLabels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
            'incomeSeries' => [32000000, 35500000, 34200000, 37000000, 36500000, $income],
            'expenseSeries' => [24500000, 26800000, 28200000, 27600000, 30100000, $expense],
        ]);
    }
}
