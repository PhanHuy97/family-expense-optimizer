<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Income;

final class IncomeController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $incomeModel = new Income($this->db());

        $this->view('incomes.index', [
            'title' => 'Khoản thu',
            'incomes' => $incomeModel->allByUser($this->currentUserId()),
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->view('incomes.create', [
            'title' => 'Thêm khoản thu',
            'income' => $this->emptyIncome(),
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();

        $data = $this->incomeDataFromRequest();
        $error = $this->validateIncomeData($data);

        if ($error !== null) {
            $this->setFlash('danger', $error);
            $this->view('incomes.create', [
                'title' => 'Thêm khoản thu',
                'income' => $data,
            ]);
            return;
        }

        $incomeModel = new Income($this->db());
        $incomeModel->create(
            $this->currentUserId(),
            $data['title'],
            (float) $data['amount'],
            $data['income_date'],
            $data['note'] === '' ? null : $data['note']
        );

        $this->setFlash('success', 'Thêm khoản thu thành công.');
        $this->redirect('/incomes');
    }

    public function edit(): void
    {
        $this->requireLogin();

        $id = (int) ($_GET['id'] ?? 0);
        $incomeModel = new Income($this->db());
        $income = $incomeModel->findByUser($id, $this->currentUserId());

        if ($income === null) {
            $this->setFlash('danger', 'Không tìm thấy khoản thu.');
            $this->redirect('/incomes');
        }

        $this->view('incomes.edit', [
            'title' => 'Sửa khoản thu',
            'income' => $income,
        ]);
    }

    public function update(): void
    {
        $this->requireLogin();

        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->incomeDataFromRequest();
        $error = $this->validateIncomeData($data);

        if ($id <= 0) {
            $error = 'Khoản thu không hợp lệ.';
        }

        if ($error !== null) {
            $data['id'] = $id;
            $this->setFlash('danger', $error);
            $this->view('incomes.edit', [
                'title' => 'Sửa khoản thu',
                'income' => $data,
            ]);
            return;
        }

        $incomeModel = new Income($this->db());
        $income = $incomeModel->findByUser($id, $this->currentUserId());

        if ($income === null) {
            $this->setFlash('danger', 'Không tìm thấy khoản thu.');
            $this->redirect('/incomes');
        }

        $incomeModel->update(
            $id,
            $this->currentUserId(),
            $data['title'],
            (float) $data['amount'],
            $data['income_date'],
            $data['note'] === '' ? null : $data['note']
        );

        $this->setFlash('success', 'Cập nhật khoản thu thành công.');
        $this->redirect('/incomes');
    }

    public function delete(): void
    {
        $this->requireLogin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->setFlash('danger', 'Khoản thu không hợp lệ.');
            $this->redirect('/incomes');
        }

        $incomeModel = new Income($this->db());
        $incomeModel->delete($id, $this->currentUserId());

        $this->setFlash('success', 'Xóa khoản thu thành công.');
        $this->redirect('/incomes');
    }

    private function currentUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    private function emptyIncome(): array
    {
        return [
            'title' => '',
            'amount' => '',
            'income_date' => date('Y-m-d'),
            'note' => '',
        ];
    }

    private function incomeDataFromRequest(): array
    {
        return [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'amount' => trim((string) ($_POST['amount'] ?? '')),
            'income_date' => trim((string) ($_POST['income_date'] ?? '')),
            'note' => trim((string) ($_POST['note'] ?? '')),
        ];
    }

    private function validateIncomeData(array $data): ?string
    {
        if ($data['title'] === '') {
            return 'Vui lòng nhập tên khoản thu.';
        }

        if ($data['amount'] === '' || !is_numeric($data['amount']) || (float) $data['amount'] <= 0) {
            return 'Số tiền phải lớn hơn 0.';
        }

        if ($data['income_date'] === '') {
            return 'Vui lòng chọn ngày thu.';
        }

        $date = \DateTime::createFromFormat('Y-m-d', $data['income_date']);
        if ($date === false || $date->format('Y-m-d') !== $data['income_date']) {
            return 'Ngày thu không hợp lệ.';
        }

        return null;
    }
}
