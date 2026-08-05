<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\AuditLogModel;

final class Audit
{
    public static function record(?int $userId, string $event, string $description = '', array $meta = []): void
    {
        try {
            $model = new AuditLogModel();
            $model->create([
                'user_id' => $userId,
                'event_type' => $event,
                'description' => $description,
                'metadata' => json_encode($meta, JSON_UNESCAPED_UNICODE),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Do not break request flow on audit failure
            Logger::getInstance()->warning('Audit record failed: ' . $e->getMessage());
        }
    }
}
