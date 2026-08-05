<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class SymptomReportModel extends BaseModel
{
    protected string $table = 'symptom_reports';

    public function createReport(array $data): int
    {
        $payload = [
            'user_id' => $data['user_id'],
            'age' => $data['age'],
            'gender' => $data['gender'],
            'symptoms' => json_encode($data['symptoms'], JSON_UNESCAPED_UNICODE),
            'duration' => $data['duration'],
            'pain_level' => $data['pain_level'],
            'temperature' => $data['temperature'],
            'medical_history' => $data['medical_history'],
            'current_medicines' => $data['current_medicines'],
            'smoking' => $data['smoking'],
            'alcohol' => $data['alcohol'],
            'exercise' => $data['exercise'],
            'family_history' => $data['family_history'],
            'conditions' => json_encode($data['conditions'], JSON_UNESCAPED_UNICODE),
            'risk_level' => $data['risk_level'],
            'emergency_warning' => $data['emergency_warning'],
            'explanation' => $data['explanation'],
            'suggested_tests' => $data['suggested_tests'],
            'confidence' => $data['confidence'],
            'follow_up_questions' => json_encode($data['follow_up_questions'], JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $this->create($payload);
    }

    public function getReportsByUserId(int $userId): array
    {
        $statement = $this->db->prepare('SELECT * FROM symptom_reports WHERE user_id = :user_id ORDER BY created_at DESC');
        $statement->execute(['user_id' => $userId]);
        $reports = $statement->fetchAll();

        return array_map([$this, 'parseReport'], $reports ?: []);
    }

    private function parseReport(array $report): array
    {
        $report['symptoms'] = json_decode((string) $report['symptoms'], true) ?: [];
        $report['conditions'] = json_decode((string) $report['conditions'], true) ?: [];
        $report['follow_up_questions'] = json_decode((string) $report['follow_up_questions'], true) ?: [];

        return $report;
    }
}
