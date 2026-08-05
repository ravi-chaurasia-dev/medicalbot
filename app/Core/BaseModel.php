<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

abstract class BaseModel
{
    protected ?PDO $db = null;
    protected string $table;

    public function __construct()
    {
        // Lazy load database connection only when needed.
    }

    protected function db(): PDO
    {
        return $this->db ??= Database::getConnection();
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        $query = sprintf('SELECT * FROM %s ORDER BY %s', $this->table, $orderBy);
        $statement = $this->db()->query($query);
        return $statement->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db()->prepare(sprintf('SELECT * FROM %s WHERE id = :id LIMIT 1', $this->table));
        $statement->execute(['id' => $id]);
        $result = $statement->fetch();
        return $result === false ? null : $result;
    }

    public function create(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);
        $statement = $this->db()->prepare(sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        ));

        $statement->execute($data);
        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = sprintf('%s = :%s', $key, $key);
        }

        $data['id'] = $id;
        $statement = $this->db()->prepare(sprintf(
            'UPDATE %s SET %s WHERE id = :id',
            $this->table,
            implode(', ', $fields)
        ));

        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db()->prepare(sprintf('DELETE FROM %s WHERE id = :id', $this->table));
        return $statement->execute(['id' => $id]);
    }

    protected function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement;
    }
}
