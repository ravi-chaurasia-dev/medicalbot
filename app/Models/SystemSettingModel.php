<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

final class SystemSettingModel extends BaseModel
{
    protected string $table = 'system_settings';

    public function getSettings(): array
    {
        $statement = $this->db()->query('SELECT setting_key, setting_value FROM system_settings');
        $rows = $statement->fetchAll();
        return array_reduce($rows, static fn (array $carry, array $row): array => $carry + [$row['setting_key'] => $row['setting_value']], []);
    }

    public function updateSettings(array $settings): bool
    {
        foreach ($settings as $key => $value) {
            $statement = $this->db()->prepare('INSERT INTO system_settings (setting_key, setting_value, created_at, updated_at) VALUES (:key, :value, NOW(), NOW()) ON DUPLICATE KEY UPDATE setting_value = :value, updated_at = NOW()');
            $statement->execute(['key' => $key, 'value' => $value]);
        }

        return true;
    }
}
