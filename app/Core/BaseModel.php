<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

abstract class BaseModel
{
    protected PDO $db;

    protected string $table;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(string $orderBy = 'id ASC'): array
    {
        $query = sprintf('SELECT * FROM `%s` ORDER BY %s', $this->table, $orderBy);
        $statement = $this->db->query($query);

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM `' . $this->table . '` WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);

        $data = $statement->fetch();

        return $data !== false ? $data : null;
    }

    public function create(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $this->table,
            implode(', ', array_map(static fn (string $column): string => '`' . $column . '`', $columns)),
            implode(', ', $placeholders)
        );

        $statement = $this->db->prepare($sql);
        $statement->execute($data);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        foreach ($data as $column => $value) {
            $fields[] = '`' . $column . '` = :' . $column;
        }

        $sql = sprintf('UPDATE `%s` SET %s WHERE id = :id', $this->table, implode(', ', $fields));
        $statement = $this->db->prepare($sql);
        $data['id'] = $id;

        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM `' . $this->table . '` WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }
}
