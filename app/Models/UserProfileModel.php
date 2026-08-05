<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class UserProfileModel extends BaseModel
{
    protected string $table = 'user_profiles';

    public function findByUserId(int $userId): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM user_profiles WHERE user_id = :user_id LIMIT 1');
        $statement->execute(['user_id' => $userId]);
        $result = $statement->fetch();
        return $result === false ? null : $result;
    }

    public function upsert(int $userId, array $data): bool
    {
        $existing = $this->findByUserId($userId);

        if ($existing) {
            $fields = [];
            $params = ['user_id' => $userId];

            foreach ($data as $key => $value) {
                $fields[] = sprintf('%s = :%s', $key, $key);
                $params[$key] = $value;
            }

            $fields[] = 'updated_at = NOW()';
            $sql = 'UPDATE user_profiles SET ' . implode(', ', $fields) . ' WHERE user_id = :user_id';
            $statement = $this->db()->prepare($sql);
            return $statement->execute($params);
        }

        $payload = $data;
        $payload['user_id'] = $userId;
        $payload['created_at'] = date('Y-m-d H:i:s');
        $payload['updated_at'] = date('Y-m-d H:i:s');

        $columns = array_keys($payload);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $statement = $this->db()->prepare(sprintf(
            'INSERT INTO user_profiles (%s) VALUES (%s)',
            implode(', ', $columns),
            implode(', ', $placeholders)
        ));

        return $statement->execute($payload);
    }
}
