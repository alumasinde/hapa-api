<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class AdminSessionRepository
{
    public function create(int $adminId): int
    {
        $now = Date::now()->format('Y-m-d H:i:s');
        Connection::get()->prepare('INSERT INTO admin_sessions (admin_user_id, created_at, last_used_at) VALUES (:admin_id, :created_at, :last_used_at)')->execute(['admin_id' => $adminId, 'created_at' => $now, 'last_used_at' => $now]);
        return (int) Connection::get()->lastInsertId();
    }

    public function active(int $sessionId, int $adminId): bool
    {
        $statement = Connection::get()->prepare('SELECT 1 FROM admin_sessions WHERE id = :id AND admin_user_id = :admin_id AND revoked_at IS NULL LIMIT 1');
        $statement->execute(['id' => $sessionId, 'admin_id' => $adminId]);
        return (bool) $statement->fetchColumn();
    }

    public function touch(int $sessionId): void
    {
        Connection::get()->prepare('UPDATE admin_sessions SET last_used_at = :now WHERE id = :id AND revoked_at IS NULL')->execute(['id' => $sessionId, 'now' => Date::now()->format('Y-m-d H:i:s')]);
    }

    public function revoke(int $sessionId, int $adminId): void
    {
        Connection::get()->prepare('UPDATE admin_sessions SET revoked_at = :now WHERE id = :id AND admin_user_id = :admin_id AND revoked_at IS NULL')->execute(['id' => $sessionId, 'admin_id' => $adminId, 'now' => Date::now()->format('Y-m-d H:i:s')]);
    }
}
