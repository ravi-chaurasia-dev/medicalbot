<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class UserModel extends BaseModel
{
    protected string $table = 'users';

    public function createUser(array $data): int
    {
        return $this->create([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password_hash' => password_hash($data['password'], PASSWORD_ARGON2ID),
            'role' => $data['role'] ?? 'patient',
            'status' => 'pending',
            'email_verified_at' => null,
            'remember_token' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => strtolower(trim($email))]);
        $result = $statement->fetch();
        return $result === false ? null : $result;
    }

    public function findByRememberToken(string $token): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM users WHERE remember_token = :token LIMIT 1');
        $statement->execute(['token' => $token]);
        $result = $statement->fetch();
        return $result === false ? null : $result;
    }

    public function setRememberToken(int $userId, ?string $token): bool
    {
        $statement = $this->db()->prepare('UPDATE users SET remember_token = :token, updated_at = NOW() WHERE id = :id');
        return $statement->execute(['token' => $token, 'id' => $userId]);
    }

    public function setPassword(int $id, string $password): bool
    {
        $statement = $this->db()->prepare('UPDATE users SET password_hash = :password_hash, updated_at = NOW() WHERE id = :id');
        return $statement->execute([
            'password_hash' => password_hash($password, PASSWORD_ARGON2ID),
            'id' => $id,
        ]);
    }

    public function verifyEmail(int $id): bool
    {
        $statement = $this->db()->prepare('UPDATE users SET email_verified_at = NOW(), status = :status, updated_at = NOW() WHERE id = :id');
        return $statement->execute([
            'status' => 'active',
            'id' => $id,
        ]);
    }

    public function isEmailVerified(int $id): bool
    {
        $statement = $this->db()->prepare('SELECT email_verified_at FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $value = $statement->fetchColumn();
        return $value !== false && $value !== null && $value !== '';
    }

    public function updateUser(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = sprintf('%s = :%s', $key, $key);
            $params[$key] = $value;
        }

        if ($fields === []) {
            return false;
        }

        $sql = sprintf('UPDATE users SET %s, updated_at = NOW() WHERE id = :id', implode(', ', $fields));
        $statement = $this->db()->prepare($sql);
        return $statement->execute($params);
    }
}
