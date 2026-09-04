<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class AuditLogRepository
{
    public function log(string $actorType, ?int $actorId, string $action, string $subjectType, ?int $subjectId, array $metadata = []): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $binaryIp = $ip ? @inet_pton($ip) : null;
        Connection::get()->prepare('INSERT INTO audit_logs (actor_type, actor_id, action, subject_type, subject_id, metadata, ip_address, created_at) VALUES (:actor_type, :actor_id, :action, :subject_type, :subject_id, :metadata, :ip_address, :created_at)')->execute([
            'actor_type' => $actorType, 'actor_id' => $actorId, 'action' => $action, 'subject_type' => $subjectType, 'subject_id' => $subjectId,
            'metadata' => $metadata === [] ? null : json_encode($metadata, JSON_THROW_ON_ERROR), 'ip_address' => $binaryIp, 'created_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
