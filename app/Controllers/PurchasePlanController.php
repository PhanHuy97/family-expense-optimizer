<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Expense;
use App\Models\Income;
use App\Models\PurchasePlan;
use App\Models\Setting;

final class PurchasePlanController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $purchasePlanModel = new PurchasePlan($this->db());

        $this->view('purchase_plans.index', [
            'title' => 'Kế hoạch mua sắm',
            'plans' => $purchasePlanModel->allByUser($this->currentUserId()),
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->view('purchase_plans.create', [
            'title' => 'Thêm kế hoạch mua sắm',
            'plan' => $this->emptyPlan(),
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        $data = $this->planDataFromRequest();
        $error = $this->validatePlanData($data);

        if ($error !== null) {
            $this->setFlash('danger', $error);
            $this->view('purchase_plans.create', [
                'title' => 'Thêm kế hoạch mua sắm',
                'plan' => $data,
            ]);
            return;
        }

        $userId = $this->currentUserId();
        $setting = (new Setting($this->db()))->findByUser($userId);
        $currentBalance = (new Income($this->db()))->totalByUser($userId) - (new Expense($this->db()))->totalByUser($userId);
        $desiredMonth = $this->normalizeMonth($data['desired_month']);
        $suggestedMonth = $this->suggestPurchaseMonth(
            $currentBalance,
            (float) $data['amount'],
            (float) ($setting['minimum_balance'] ?? 0),
            (float) ($setting['expected_monthly_income'] ?? 0),
            (float) ($setting['expected_monthly_expense'] ?? 0),
            $desiredMonth
        );
        $status = $this->resolveStatus($suggestedMonth, $desiredMonth);

        $purchasePlanModel = new PurchasePlan($this->db());
        $purchasePlanModel->create(
            $userId,
            $data['title'],
            (float) $data['amount'],
            $desiredMonth,
            $suggestedMonth,
            $status,
            $data['note'] === '' ? null : $data['note']
        );

        $this->setFlash('success', 'Thêm kế hoạch mua sắm thành công.');
        $this->redirect('/purchase-plans');
    }

    public function delete(): void
    {
        $this->requireLogin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Kế hoạch mua sắm không hợp lệ.');
            $this->redirect('/purchase-plans');
        }

        $purchasePlanModel = new PurchasePlan($this->db());
        $purchasePlanModel->delete($id, $this->currentUserId());

        $this->setFlash('success', 'Xóa kế hoạch mua sắm thành công.');
        $this->redirect('/purchase-plans');
    }

    private function currentUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    private function emptyPlan(): array
    {
        return [
            'title' => '',
            'amount' => '',
            'desired_month' => date('Y-m'),
            'note' => '',
        ];
    }

    private function planDataFromRequest(): array
    {
        return [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'amount' => trim((string) ($_POST['amount'] ?? '')),
            'desired_month' => trim((string) ($_POST['desired_month'] ?? '')),
            'note' => trim((string) ($_POST['note'] ?? '')),
        ];
    }

    private function validatePlanData(array $data): ?string
    {
        if ($data['title'] === '') {
            return 'Vui lòng nhập tên món muốn mua.';
        }

        if ($data['amount'] === '' || !is_numeric($data['amount']) || (float) $data['amount'] <= 0) {
            return 'Số tiền phải lớn hơn 0.';
        }

        if ($data['desired_month'] === '') {
            return 'Vui lòng chọn tháng mong muốn.';
        }

        $date = \DateTime::createFromFormat('Y-m', $data['desired_month']);
        if ($date === false || $date->format('Y-m') !== $data['desired_month']) {
            return 'Tháng mong muốn không hợp lệ.';
        }

        return null;
    }

    private function normalizeMonth(string $month): string
    {
        return (new \DateTime($month . '-01'))->format('Y-m-01');
    }

    private function suggestPurchaseMonth(
        float $currentBalance,
        float $planAmount,
        float $minimumBalance,
        float $expectedIncome,
        float $expectedExpense,
        string $desiredMonth
    ): ?string {
        $balance = $currentBalance;
        $month = new \DateTime($desiredMonth);

        for ($i = 0; $i < 12; $i++) {
            if (($balance - $planAmount) >= $minimumBalance) {
                return $month->format('Y-m-01');
            }

            $balance = $balance + $expectedIncome - $expectedExpense;
            $month->modify('+1 month');
        }

        return null;
    }

    private function resolveStatus(?string $suggestedMonth, string $desiredMonth): string
    {
        if ($suggestedMonth === null) {
            return 'not_found';
        }

        if ($suggestedMonth === $desiredMonth) {
            return 'can_buy';
        }

        return 'delayed';
    }
}
