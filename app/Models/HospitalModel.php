<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class HospitalModel extends BaseModel
{
    protected string $table = 'hospitals';

    public function getAllSpecialties(): array
    {
        $statement = $this->db->query('SELECT DISTINCT specialty FROM doctors ORDER BY specialty ASC');
        return array_column($statement->fetchAll(), 'specialty');
    }

    public function searchHospitals(array $filters): array
    {
        $sql = 'SELECT h.*, 0 AS distance FROM hospitals h';
        $params = [];

        if (! empty($filters['latitude']) && ! empty($filters['longitude'])) {
            $sql = 'SELECT h.*, (
                6371 * acos(
                    cos(radians(:lat)) * cos(radians(h.latitude)) * cos(radians(h.longitude) - radians(:lng)) +
                    sin(radians(:lat)) * sin(radians(h.latitude))
                )
            ) AS distance FROM hospitals h';
            $params['lat'] = $filters['latitude'];
            $params['lng'] = $filters['longitude'];
        }

        $sql .= ' WHERE 1=1';

        if (! empty($filters['search'])) {
            $sql .= ' AND (h.name LIKE :search OR h.address LIKE :search OR h.departments LIKE :search)';
            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (! empty($filters['specialty'])) {
            $sql .= ' AND h.departments LIKE :specialty';
            $params['specialty'] = '%' . $filters['specialty'] . '%';
        }

        if ($filters['rating'] > 0) {
            $sql .= ' AND h.rating >= :rating';
            $params['rating'] = $filters['rating'];
        }

        if ($filters['emergency'] !== '') {
            $sql .= ' AND h.emergency_available = :emergency';
            $params['emergency'] = $filters['emergency'] === 'yes' ? '1' : '0';
        }

        if (! empty($filters['latitude']) && ! empty($filters['longitude']) && ! empty($filters['distance'])) {
            $sql .= ' HAVING distance <= :max_distance';
            $params['max_distance'] = $filters['distance'];
        }

        $sql .= ' ORDER BY ';
        if (! empty($filters['latitude']) && ! empty($filters['longitude'])) {
            $sql .= 'distance ASC, rating DESC';
        } else {
            $sql .= 'rating DESC, name ASC';
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function create(array $data): int
    {
        return parent::create($data);
    }

    public function updateHospital(int $id, array $data): bool
    {
        return parent::update($id, $data);
    }

    public function deleteHospital(int $id): bool
    {
        return parent::delete($id);
    }
}
