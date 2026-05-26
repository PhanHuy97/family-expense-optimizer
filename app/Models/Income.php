<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Income
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function allByUser(int $userId): array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM incomes WHERE user_id = :user_id ORDER BY income_date DESC, id DESC'
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    public function findByUser(int $id, int $userId): ?array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM incomes WHERE id = :id AND user_id = :user_id LIMIT 1'
        );
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);

        $income = $statement->fetch();

        return is_array($income) ? $income : null;
    }

    public function create(int $userId, string $title, float $amount, string $incomeDate, ?string $note): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO incomes (user_id, title, amount, income_date, note)
             VALUES (:user_id, :title, :amount, :income_date, :note)'
        );
        $statement->execute([
            'user_id' => $userId,
            'title' => $title,
            'amount' => $amount,
            'income_date' => $incomeDate,
            'note' => $note,
        ]);
    }

    public function update(int $id, int $userId, string $title, float $amount, string $incomeDate, ?string $note): void
    {
        $statement = $this->db->prepare(
            'UPDATE incomes
             SET title = :title, amount = :amount, income_date = :income_date, note = :note
             WHERE id = :id AND user_id = :user_id'
        );
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
            'title' => $title,
            'amount' => $amount,
            'income_date' => $incomeDate,
            'note' => $note,
        ]);
    }

    public function delete(int $id, int $userId): void
    {
        $statement = $this->db->prepare('DELETE FROM incomes WHERE id = :id AND user_id = :user_id');
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }
}
