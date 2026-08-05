<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class LabReportModel extends BaseModel
{
    protected string $table = 'lab_reports';

    public function findById(int $id): ?array
    {
        $statement = $this->db()->prepare('SELECT * FROM lab_reports WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $result = $statement->fetch();
        return $result === false ? null : $result;
    }

    public function searchReports(int $userId, array $filters = []): array
    {
        $sql = 'SELECT * FROM lab_reports WHERE user_id = :user_id';
        $params = ['user_id' => $userId];

        if (! empty($filters['search'])) {
            $sql .= ' AND (original_file_name LIKE :search OR explanation LIKE :search OR report_summary LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (! empty($filters['status'])) {
            $sql .= ' AND risk_level = :status';
            $params['status'] = $filters['status'];
        }

        if (! empty($filters['start_date'])) {
            $sql .= ' AND created_at >= :start_date';
            $params['start_date'] = $filters['start_date'] . ' 00:00:00';
        }

        if (! empty($filters['end_date'])) {
            $sql .= ' AND created_at <= :end_date';
            $params['end_date'] = $filters['end_date'] . ' 23:59:59';
        }

        $sql .= ' ORDER BY created_at DESC';
        $statement = $this->db()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function deleteReport(int $id): bool
    {
        return $this->delete($id);
    }
}
