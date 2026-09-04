<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class FlashEngagementRepository
{
    public function recordView(int $flashId, string $viewerKey): void
    {
        $now = Date::now();
        Connection::get()->prepare(
            'INSERT IGNORE INTO flash_views (flash_id, viewer_key, viewed_on, created_at)
             VALUES (:flash_id, :viewer_key, :viewed_on, :created_at)'
        )->execute([
            'flash_id' => $flashId,
            'viewer_key' => $viewerKey,
            'viewed_on' => $now->format('Y-m-d'),
            'created_at' => $now->format('Y-m-d H:i:s'),
        ]);
    }

    public function markHelpful(int $flashId, int $userId): void
    {
        Connection::get()->prepare(
            'INSERT IGNORE INTO flash_helpful_reactions (flash_id, user_id, created_at)
             VALUES (:flash_id, :user_id, :created_at)'
        )->execute([
            'flash_id' => $flashId,
            'user_id' => $userId,
            'created_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function removeHelpful(int $flashId, int $userId): void
    {
        Connection::get()->prepare(
            'DELETE FROM flash_helpful_reactions WHERE flash_id = :flash_id AND user_id = :user_id'
        )->execute(['flash_id' => $flashId, 'user_id' => $userId]);
    }

    public function recordShare(int $flashId, ?int $userId): void
    {
        Connection::get()->prepare(
            'INSERT INTO flash_share_events (flash_id, user_id, created_at)
             VALUES (:flash_id, :user_id, :created_at)'
        )->execute([
            'flash_id' => $flashId,
            'user_id' => $userId,
            'created_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function stats(int $flashId, ?int $userId = null): array
    {
        $pdo = Connection::get();
        $views = $pdo->prepare('SELECT COUNT(*) FROM flash_views WHERE flash_id = :flash_id');
        $views->execute(['flash_id' => $flashId]);

        $helpful = $pdo->prepare('SELECT COUNT(*) FROM flash_helpful_reactions WHERE flash_id = :flash_id');
        $helpful->execute(['flash_id' => $flashId]);

        $shares = $pdo->prepare('SELECT COUNT(*) FROM flash_share_events WHERE flash_id = :flash_id');
        $shares->execute(['flash_id' => $flashId]);

        $markedHelpful = false;
        if ($userId !== null) {
            $mine = $pdo->prepare(
                'SELECT 1 FROM flash_helpful_reactions WHERE flash_id = :flash_id AND user_id = :user_id LIMIT 1'
            );
            $mine->execute(['flash_id' => $flashId, 'user_id' => $userId]);
            $markedHelpful = (bool) $mine->fetchColumn();
        }

        return [
            'views' => (int) $views->fetchColumn(),
            'shares' => (int) $shares->fetchColumn(),
            'helpful' => (int) $helpful->fetchColumn(),
            'marked_helpful' => $markedHelpful,
        ];
    }
}
