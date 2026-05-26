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
