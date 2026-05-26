<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Expense
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function allByUser(int $userId): array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM expenses WHERE user_id = :user_id ORDER BY expense_date DESC, id DESC'
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    public function totalByUser(int $userId): float
    {
        $statement = $this->db->prepare('SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);

        return (float) $statement->fetchColumn();
    }

    public function totalByUserBetween(int $userId, string $startDate, string $endDate): float
    {
        $statement = $this->db->prepare(
            'SELECT COALESCE(SUM(amount), 0)
             FROM expenses
             WHERE user_id = :user_id
               AND expense_date >= :start_date
               AND expense_date <= :end_date'
        );
        $statement->execute([
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return (float) $statement->fetchColumn();
    }

    public function monthlyTotals(int $userId, string $startDate, string $endDate): array
    {
        $statement = $this->db->prepare(
            "SELECT DATE_FORMAT(expense_date, '%Y-%m') AS month_key, COALESCE(SUM(amount), 0) AS total
             FROM expenses
             WHERE user_id = :user_id
               AND expense_date >= :start_date
               AND expense_date <= :end_date
             GROUP BY month_key"
        );
        $statement->execute([
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $totals = [];
        foreach ($statement->fetchAll() as $row) {
            $totals[(string) $row['month_key']] = (float) $row['total'];
        }

        return $totals;
    }

    public function findByUser(int $id, int $userId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM expenses WHERE id = :id AND user_id = :user_id LIMIT 1'
        );
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);

        $expense = $statement->fetch();

        return is_array($expense) ? $expense : null;
    }

    public function create(
        int $userId,
        string $category,
        string $title,
        float $amount,
        string $expenseDate,
        ?string $note
    ): void {
        $statement = $this->db->prepare(
            'INSERT INTO expenses (user_id, category, title, amount, expense_date, note)
             VALUES (:user_id, :category, :title, :amount, :expense_date, :note)'
        );
        $statement->execute([
            'user_id' => $userId,
            'category' => $category,
            'title' => $title,
            'amount' => $amount,
            'expense_date' => $expenseDate,
            'note' => $note,
        ]);
    }

    public function update(
        int $id,
        int $userId,
        string $category,
        string $title,
        float $amount,
        string $expenseDate,
        ?string $note
    ): void {
        $statement = $this->db->prepare(
            'UPDATE expenses
             SET category = :category, title = :title, amount = :amount, expense_date = :expense_date, note = :note
             WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
            'category' => $category,
            'title' => $title,
            'amount' => $amount,
            'expense_date' => $expenseDate,
            'note' => $note,
        ]);
    }

    public function delete(int $id, int $userId): void
    {
        $statement = $this->db->prepare('DELETE FROM expenses WHERE id = :id AND user_id = :user_id');
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }
}
