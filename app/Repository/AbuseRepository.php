<?php
declare(strict_types=1);
namespace App\Repository;

use App\Database\Connection;

final class AbuseRepository
{
    public function record(?int $userId, string $type, int $severity = 1, ?string $subjectType = null, ?int $subjectId = null, array $metadata = []): void
    {
        Connection::get()->prepare('INSERT INTO abuse_events (user_id, event_type, severity, subject_type, subject_id, metadata, created_at) VALUES (:user_id, :event_type, :severity, :subject_type, :subject_id, :metadata, UTC_TIMESTAMP())')->execute([
            'user_id'=>$userId,'event_type'=>$type,'severity'=>$severity,'subject_type'=>$subjectType,'subject_id'=>$subjectId,
            'metadata'=>$metadata === [] ? null : json_encode($metadata, JSON_THROW_ON_ERROR),
        ]);
    }

    public function recentCount(int $userId, int $seconds = 86400): int
    {
        $s=Connection::get()->prepare('SELECT COUNT(*) FROM abuse_events WHERE user_id = :id AND created_at >= UTC_TIMESTAMP() - INTERVAL :seconds SECOND');
        $s->bindValue('id',$userId,\PDO::PARAM_INT);
        $s->bindValue('seconds',$seconds,\PDO::PARAM_INT);
        $s->execute();
        return (int)$s->fetchColumn();
    }
}