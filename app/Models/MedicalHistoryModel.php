<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class MedicalHistoryModel extends BaseModel
{
    protected string $table = 'medical_histories';

    public function findByUserId(int $userId): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM medical_histories WHERE user_id = :user_id LIMIT 1');
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
            $sql = 'UPDATE medical_histories SET ' . implode(', ', $fields) . ' WHERE user_id = :user_id';
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
            'INSERT INTO medical_histories (%s) VALUES (%s)',
            implode(', ', $columns),
            implode(', ', $placeholders)
        ));

        return $statement->execute($payload);
    }
}
