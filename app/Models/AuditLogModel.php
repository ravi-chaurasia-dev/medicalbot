<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;
use PDO;

final class AuditLogModel extends BaseModel
{
    protected string $table = 'audit_logs';

    public function getRecentLogs(int $limit = 50): array
    {
        $statement = $this->db->prepare('SELECT al.*, u.name AS user_name FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT :limit');
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
