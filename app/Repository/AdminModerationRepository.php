<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class AdminModerationRepository
{
    public function reportedFlashes(int $limit = 50): array
    {
        $statement = Connection::get()->prepare('SELECT f.id, f.user_id, f.description, f.area_name, f.moderation_status, f.created_at, COUNT(fr.id) AS report_count FROM flashes f JOIN flash_reports fr ON fr.flash_id = f.id AND fr.status = \'open\' GROUP BY f.id ORDER BY report_count DESC, MIN(fr.created_at) ASC LIMIT ' . $limit);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function reports(int $flashId): array
    {
        $statement = Connection::get()->prepare('SELECT fr.id, fr.reason, fr.description, fr.status, fr.created_at, u.id AS reporter_id, u.display_name FROM flash_reports fr JOIN users u ON u.id = fr.reporter_user_id WHERE fr.flash_id = :flash_id ORDER BY fr.created_at DESC');
        $statement->execute(['flash_id' => $flashId]);
        return $statement->fetchAll();
    }

    public function flashExists(int $id): bool
    {
        $statement = Connection::get()->prepare('SELECT 1 FROM flashes WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        return (bool) $statement->fetchColumn();
    }

    public function moderate(int $flashId, string $status, string $reason, int $adminId): void
    {
        $now = Date::now()->format('Y-m-d H:i:s');
        $pdo = Connection::get();
        $pdo->beginTransaction();
        try {
            $pdo->prepare('UPDATE flashes SET moderation_status = :status, updated_at = :now WHERE id = :id')->execute(['id' => $flashId, 'status' => $status, 'now' => $now]);
            $reportStatus = $status === 'visible' ? 'dismissed' : 'actioned';
            $pdo->prepare('UPDATE flash_reports SET status = :status, updated_at = :now WHERE flash_id = :flash_id AND status = \'open\'')->execute(['status' => $reportStatus, 'now' => $now, 'flash_id' => $flashId]);
            $pdo->commit();
        } catch (\Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }
}
