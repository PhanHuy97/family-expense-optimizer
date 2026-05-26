<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Setting;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $incomeModel = new Income($this->db());
        $expenseModel = new Expense($this->db());
        $settingModel = new Setting($this->db());

        $monthStart = date('Y-m-01');
        $monthEnd = date('Y-m-t');

        $income = $incomeModel->totalByUserBetween($userId, $monthStart, $monthEnd);
        $expense = $expenseModel->totalByUserBetween($userId, $monthStart, $monthEnd);
        $balance = $incomeModel->totalByUser($userId) - $expenseModel->totalByUser($userId);

        $setting = $settingModel->findByUser($userId);
        $budgetLimit = (float) ($setting['monthly_budget'] ?? 0);
        $budgetUsage = $budgetLimit > 0 ? ($expense / $budgetLimit * 100) : 0;

        $chartData = $this->buildSixMonthChartData($incomeModel, $expenseModel, $userId);

        $this->view('dashboard.index', [
            'title' => 'Dashboard',
            'income' => $income,
            'expense' => $expense,
            'balance' => $balance,
            'budgetLimit' => $budgetLimit,
            'budgetUsage' => $budgetUsage,
            'isBudgetWarning' => $budgetLimit > 0 && $budgetUsage >= 90,
            'chartLabels' => $chartData['labels'],
            'incomeSeries' => $chartData['income'],
            'expenseSeries' => $chartData['expense'],
        ]);
    }

    private function buildSixMonthChartData(Income $incomeModel, Expense $expenseModel, int $userId): array
    {
        $labels = [];
        $incomeSeries = [];
        $expenseSeries = [];

        $start = new \DateTime('first day of this month');
        $start->modify('-5 months');
        $end = new \DateTime('last day of this month');

        $incomeTotals = $incomeModel->monthlyTotals($userId, $start->format('Y-m-d'), $end->format('Y-m-d'));
        $expenseTotals = $expenseModel->monthlyTotals($userId, $start->format('Y-m-d'), $end->format('Y-m-d'));

        for ($i = 0; $i < 6; $i++) {
            $month = (clone $start)->modify("+{$i} months");
            $key = $month->format('Y-m');

            $labels[] = 'Tháng ' . $month->format('n/Y');
            $incomeSeries[] = $incomeTotals[$key] ?? 0;
            $expenseSeries[] = $expenseTotals[$key] ?? 0;
        }

        return [
            'labels' => $labels,
            'income' => $incomeSeries,
            'expense' => $expenseSeries,
        ];
    }
}
