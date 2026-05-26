<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;

final class SettingController extends Controller
{
    public function edit(): void
    {
        $this->requireLogin();

        $settingModel = new Setting($this->db());

        $this->view('settings.edit', [
            'title' => 'Thiết lập tài chính',
            'setting' => $settingModel->findByUser($this->currentUserId()),
        ]);
    }

    public function update(): void
    {
        $this->requireLogin();

        $data = $this->settingDataFromRequest();
        $error = $this->validateSettingData($data);

        if ($error !== null) {
            $this->setFlash('danger', $error);
            $this->view('settings.edit', [
                'title' => 'Thiết lập tài chính',
                'setting' => $data,
            ]);
            return;
        }

        $settingModel = new Setting($this->db());
        $settingModel->update(
            $this->currentUserId(),
            (float) $data['monthly_budget'],
            (float) $data['minimum_balance'],
            (float) $data['expected_monthly_income'],
            (float) $data['expected_monthly_expense']
        );

        $this->setFlash('success', 'Cập nhật thiết lập tài chính thành công.');
        $this->redirect('/settings');
    }

    private function currentUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    private function settingDataFromRequest(): array
    {
        return [
            'monthly_budget' => trim((string) ($_POST['monthly_budget'] ?? '0')),
            'minimum_balance' => trim((string) ($_POST['minimum_balance'] ?? '0')),
            'expected_monthly_income' => trim((string) ($_POST['expected_monthly_income'] ?? '0')),
            'expected_monthly_expense' => trim((string) ($_POST['expected_monthly_expense'] ?? '0')),
        ];
    }

    private function validateSettingData(array $data): ?string
    {
        $labels = [
            'monthly_budget' => 'Hạn mức chi tiêu tháng',
            'minimum_balance' => 'Số dư tối thiểu',
            'expected_monthly_income' => 'Thu nhập dự kiến mỗi tháng',
            'expected_monthly_expense' => 'Chi tiêu dự kiến mỗi tháng',
        ];

        foreach ($labels as $field => $label) {
            if ($data[$field] === '' || !is_numeric($data[$field])) {
                return "{$label} phải là số.";
            }

            if ((float) $data[$field] < 0) {
                return "{$label} không được nhỏ hơn 0.";
            }
        }

        return null;
    }
}
