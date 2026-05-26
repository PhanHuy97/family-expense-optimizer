<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class PurchasePlan
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function allByUser(int $userId): array
    {
        $statement = $this->db->prepare(
            'SELECT * FROM purchase_plans WHERE user_id = :user_id ORDER BY desired_month ASC, id DESC'
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    public function create(
        int $userId,
        string $title,
        float $amount,
        string $desiredMonth,
        ?string $suggestedMonth,
        string $status,
        ?string $note
    ): void {
        $statement = $this->db->prepare(
            'INSERT INTO purchase_plans (
                user_id,
                title,
                amount,
                desired_month,
                suggested_month,
                status,
                note
            ) VALUES (
                :user_id,
                :title,
                :amount,
                :desired_month,
                :suggested_month,
                :status,
                :note
            )'
        );
        $statement->execute([
            'user_id' => $userId,
            'title' => $title,
            'amount' => $amount,
            'desired_month' => $desiredMonth,
            'suggested_month' => $suggestedMonth,
            'status' => $status,
            'note' => $note,
        ]);
    }

    public function delete(int $id, int $userId): void
    {
        $statement = $this->db->prepare('DELETE FROM purchase_plans WHERE id = :id AND user_id = :user_id');
        $statement->execute([
            'id' => $id,
            'user_id' => $userId,
        ]);
    }
}
