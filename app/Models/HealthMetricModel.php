<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;
use PDO;

final class HealthMetricModel extends BaseModel
{
    protected string $table = 'health_metrics';

    public function getMetricsByUserId(int $userId, string $type, int $limit = 50): array
    {
        $statement = $this->db->prepare('SELECT * FROM health_metrics WHERE user_id = :user_id AND metric_type = :metric_type ORDER BY recorded_at DESC LIMIT :limit');
        $statement->bindValue(':user_id', $userId, 
            
            
            
            
            
            
            
            
            
            PDO::PARAM_INT);
        $statement->bindValue(':metric_type', $type, PDO::PARAM_STR);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getRecentMetrics(int $userId, int $limit = 10): array
    {
        $statement = $this->db->prepare('SELECT * FROM health_metrics WHERE user_id = :user_id ORDER BY recorded_at DESC LIMIT :limit');
        $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function insertMetric(array $data): int
    {
        $payload = [
            'user_id' => $data['user_id'],
            'metric_type' => $data['metric_type'],
            'value' => $data['value'] ?? null,
            'systolic' => $data['systolic'] ?? null,
            'diastolic' => $data['diastolic'] ?? null,
            'unit' => $data['unit'] ?? null,
            'recorded_at' => $data['recorded_at'] ?? date('Y-m-d H:i:s'),
            'note' => $data['note'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->create($payload);
    }
}
