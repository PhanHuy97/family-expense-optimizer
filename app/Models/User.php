<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    }

    public function create(string $name, string $email, string $password): int
    {
        $this->db->beginTransaction();

        try {
            $statement = $this->db->prepare(
                'INSERT INTO users (name, email, password) VALUES (:name, :email, :password)'
            );
            $statement->execute([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $userId = (int) $this->db->lastInsertId();

            $settingStatement = $this->db->prepare(
                'INSERT INTO settings (
                    user_id,
                    monthly_budget,
                    minimum_balance,
                    expected_monthly_income,
                    expected_monthly_expense
                ) VALUES (:user_id, 0, 0, 0, 0)'
            );
            $settingStatement->execute(['user_id' => $userId]);

            $this->db->commit();

            return $userId;
        } catch (\Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }
}
