<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Setting
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUser(int $userId): array
    {
        $statement = $this->db->prepare('SELECT * FROM settings WHERE user_id = :user_id LIMIT 1');
        $statement->execute(['user_id' => $userId]);
        $setting = $statement->fetch();

        if (is_array($setting)) {
            return $setting;
        }

        return [
            'user_id' => $userId,
            'monthly_budget' => 0,
            'minimum_balance' => 0,
            'expected_monthly_income' => 0,
            'expected_monthly_expense' => 0,
        ];
    }

    public function update(
        int $userId,
        float $monthlyBudget,
        float $minimumBalance,
        float $expectedMonthlyIncome,
        float $expectedMonthlyExpense
    ): void {
        $statement = $this->db->prepare(
            'INSERT INTO settings (
                user_id,
                monthly_budget,
                minimum_balance,
                expected_monthly_income,
                expected_monthly_expense
            ) VALUES (
                :user_id,
                :monthly_budget,
                :minimum_balance,
                :expected_monthly_income,
                :expected_monthly_expense
            )
            ON DUPLICATE KEY UPDATE
                monthly_budget = VALUES(monthly_budget),
                minimum_balance = VALUES(minimum_balance),
                expected_monthly_income = VALUES(expected_monthly_income),
                expected_monthly_expense = VALUES(expected_monthly_expense)'
        );
        $statement->execute([
            'user_id' => $userId,
            'monthly_budget' => $monthlyBudget,
            'minimum_balance' => $minimumBalance,
            'expected_monthly_income' => $expectedMonthlyIncome,
            'expected_monthly_expense' => $expectedMonthlyExpense,
        ]);
    }
}
