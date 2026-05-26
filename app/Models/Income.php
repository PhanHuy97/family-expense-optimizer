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

    public function totalByUser(int $userId): float
    {
        $statement = $this->db->prepare('SELECT COALESCE(SUM(amount), 0) FROM incomes WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);

        return (float) $statement->fetchColumn();
    }

    public function totalByUserBetween(int $userId, string $startDate, string $endDate): float
    {
        $statement = $this->db->prepare(
            'SELECT COALESCE(SUM(amount), 0)
             FROM incomes
             WHERE user_id = :user_id
               AND income_date >= :start_date
               AND income_date <= :end_date'
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
            "SELECT DATE_FORMAT(income_date, '%Y-%m') AS month_key, COALESCE(SUM(amount), 0) AS total
             FROM incomes
             WHERE user_id = :user_id
               AND income_date >= :start_date
               AND income_date <= :end_date
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
