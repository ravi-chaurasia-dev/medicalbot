<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class DoctorModel extends BaseModel
{
    protected string $table = 'doctors';

    public function findBySpecialty(string $specialty): array
    {
        if ($specialty === '') {
            return [];
        }

        $statement = $this->db()->prepare('SELECT d.*, h.name AS hospital_name, h.phone AS hospital_phone FROM doctors d JOIN hospitals h ON d.hospital_id = h.id WHERE d.specialty = :specialty ORDER BY d.last_name ASC');
        $statement->execute(['specialty' => $specialty]);
        return $statement->fetchAll();
    }

    public function searchDoctors(array $filters): array
    {
        $sql = 'SELECT d.*, h.name AS hospital_name FROM doctors d JOIN hospitals h ON d.hospital_id = h.id WHERE 1=1';
        $params = [];

        if (! empty($filters['search'])) {
            $sql .= ' AND (d.first_name LIKE :search OR d.last_name LIKE :search OR d.specialty LIKE :search OR h.name LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (! empty($filters['specialty'])) {
            $sql .= ' AND d.specialty = :specialty';
            $params['specialty'] = $filters['specialty'];
        }

        if (! empty($filters['hospital_id'])) {
            $sql .= ' AND d.hospital_id = :hospital_id';
            $params['hospital_id'] = $filters['hospital_id'];
        }

        $sql .= ' ORDER BY d.last_name ASC';

        $statement = $this->db()->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }
}
