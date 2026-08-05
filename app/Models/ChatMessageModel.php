<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;
use PDO;

final class ChatMessageModel extends BaseModel
{
    protected string $table = 'chat_messages';

    public function getRecentMessagesByUserId(int $userId, int $limit = 10): array
    {
        $statement = $this->db()->prepare('SELECT * FROM chat_messages WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit');
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getRecentMessages(int $limit = 50): array
    {
        $statement = $this->db()->prepare('SELECT cm.*, u.name AS user_name FROM chat_messages cm LEFT JOIN users u ON cm.user_id = u.id ORDER BY cm.created_at DESC LIMIT :limit');
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function createMessage(array $data): int
    {
        $payload = [
            'user_id' => $data['user_id'],
            'sender' => $data['sender'],
            'message' => $data['message'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->create($payload);
    }
}
