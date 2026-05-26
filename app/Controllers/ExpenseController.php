<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Expense;

final class ExpenseController extends Controller
{
    private const CATEGORIES = [
        'Ăn uống',
        'Đi lại',
        'Học tập',
        'Điện nước',
        'Mua sắm',
        'Y tế',
        'Khác',
    ];

    public function index(): void
    {
        $this->requireLogin();

        $expenseModel = new Expense($this->db());

        $this->view('expenses.index', [
            'title' => 'Khoản chi',
            'expenses' => $expenseModel->allByUser($this->currentUserId()),
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->view('expenses.create', [
            'title' => 'Thêm khoản chi',
            'expense' => $this->emptyExpense(),
            'categories' => self::CATEGORIES,
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        $data = $this->expenseDataFromRequest();
        $error = $this->validateExpenseData($data);

        if ($error !== null) {
            $this->setFlash('danger', $error);
            $this->view('expenses.create', [
                'title' => 'Thêm khoản chi',
                'expense' => $data,
                'categories' => self::CATEGORIES,
            ]);
            return;
        }

        $expenseModel = new Expense($this->db());
        $expenseModel->create(
            $this->currentUserId(),
            $data['category'],
            $data['title'],
            (float) $data['amount'],
            $data['expense_date'],
            $data['note'] === '' ? null : $data['note']
        );

        $this->setFlash('success', 'Thêm khoản chi thành công.');
        $this->redirect('/expenses');
    }

    public function edit(): void
    {
        $this->requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $expenseModel = new Expense($this->db());
        $expense = $expenseModel->findByUser($id, $this->currentUserId());

        if ($expense === null) {
            $this->setFlash('danger', 'Không tìm thấy khoản chi.');
            $this->redirect('/expenses');
        }

        $this->view('expenses.edit', [
            'title' => 'Sửa khoản chi',
            'expense' => $expense,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function update(): void
    {
        $this->requireLogin();

        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->expenseDataFromRequest();
        $error = $this->validateExpenseData($data);

        if ($id <= 0) {
            $error = 'Khoản chi không hợp lệ.';
        }

        if ($error !== null) {
            $data['id'] = $id;
            $this->setFlash('danger', $error);
            $this->view('expenses.edit', [
                'title' => 'Sửa khoản chi',
                'expense' => $data,
                'categories' => self::CATEGORIES,
            ]);
            return;
        }

        $expenseModel = new Expense($this->db());
        $expense = $expenseModel->findByUser($id, $this->currentUserId());

        if ($expense === null) {
            $this->setFlash('danger', 'Không tìm thấy khoản chi.');
            $this->redirect('/expenses');
        }

        $expenseModel->update(
            $id,
            $this->currentUserId(),
            $data['category'],
            $data['title'],
            (float) $data['amount'],
            $data['expense_date'],
            $data['note'] === '' ? null : $data['note']
        );

        $this->setFlash('success', 'Cập nhật khoản chi thành công.');
        $this->redirect('/expenses');
    }

    public function delete(): void
    {
        $this->requireLogin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Khoản chi không hợp lệ.');
            $this->redirect('/expenses');
        }

        $expenseModel = new Expense($this->db());
        $expenseModel->delete($id, $this->currentUserId());

        $this->setFlash('success', 'Xóa khoản chi thành công.');
        $this->redirect('/expenses');
    }

    private function currentUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    private function emptyExpense(): array
    {
        return [
            'category' => 'Khác',
            'title' => '',
            'amount' => '',
            'expense_date' => date('Y-m-d'),
            'note' => '',
        ];
    }

    private function expenseDataFromRequest(): array
    {
        return [
            'category' => trim((string) ($_POST['category'] ?? '')),
            'title' => trim((string) ($_POST['title'] ?? '')),
            'amount' => trim((string) ($_POST['amount'] ?? '')),
            'expense_date' => trim((string) ($_POST['expense_date'] ?? '')),
            'note' => trim((string) ($_POST['note'] ?? '')),
        ];
    }

    private function validateExpenseData(array $data): ?string
    {
        if (!in_array($data['category'], self::CATEGORIES, true)) {
            return 'Loại chi không hợp lệ.';
        }

        if ($data['title'] === '') {
            return 'Vui lòng nhập tên khoản chi.';
        }

        if ($data['amount'] === '' || !is_numeric($data['amount']) || (float) $data['amount'] <= 0) {
            return 'Số tiền phải lớn hơn 0.';
        }

        if ($data['expense_date'] === '') {
            return 'Vui lòng chọn ngày chi.';
        }

        $date = \DateTime::createFromFormat('Y-m-d', $data['expense_date']);
        if ($date === false || $date->format('Y-m-d') !== $data['expense_date']) {
            return 'Ngày chi không hợp lệ.';
        }

        return null;
    }
}
