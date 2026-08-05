<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class LabReportValueModel extends BaseModel
{
    protected string $table = 'lab_report_values';

    public function getValuesByReportId(int $reportId): array
    {
        $statement = $this->db->prepare('SELECT * FROM lab_report_values WHERE report_id = :report_id ORDER BY id ASC');
        $statement->execute(['report_id' => $reportId]);
        return $statement->fetchAll();
    }

    public function addValues(int $reportId, array $values): bool
    {
        $this->deleteByReportId($reportId);

        $sql = 'INSERT INTO lab_report_values (report_id, analyte, value, unit, status, normal_range, note, created_at, updated_at) VALUES (:report_id, :analyte, :value, :unit, :status, :normal_range, :note, NOW(), NOW())';
        $statement = $this->db->prepare($sql);

        foreach ($values as $value) {
            $statement->execute([
                'report_id' => $reportId,
                'analyte' => $value['analyte'],
                'value' => (string) $value['value'],
                'unit' => $value['unit'] ?? '',
                'status' => $value['status'] ?? 'normal',
                'normal_range' => $value['normal_range'] ?? '',
                'note' => $value['note'] ?? '',
            ]);
        }

        return true;
    }

    public function deleteByReportId(int $reportId): bool
    {
        $statement = $this->db->prepare('DELETE FROM lab_report_values WHERE report_id = :report_id');
        return $statement->execute(['report_id' => $reportId]);
    }
}
