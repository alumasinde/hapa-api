<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class SessionRepository
{
    public function create(int $userId, string $tokenHash, ?string $deviceId, ?string $platform, int $ttl): void
    {
        Connection::get()->prepare('INSERT INTO user_sessions (user_id, refresh_token_hash, device_id, platform, expires_at, created_at) VALUES (:user_id, :token_hash, :device_id, :platform, :expires_at, :created_at)')->execute([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'device_id' => $deviceId,
            'platform' => $platform,
            'expires_at' => Date::now()->modify(sprintf('+%d seconds', $ttl))->format('Y-m-d H:i:s'),
            'created_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function findActive(string $tokenHash): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM user_sessions WHERE refresh_token_hash = :token_hash AND revoked_at IS NULL AND expires_at > UTC_TIMESTAMP() LIMIT 1');
        $statement->execute(['token_hash' => $tokenHash]);

        return $statement->fetch() ?: null;
    }

    public function rotate(int $id, string $newHash, int $ttl): void
    {
        Connection::get()->prepare('UPDATE user_sessions SET refresh_token_hash = :token_hash, expires_at = :expires_at, last_used_at = :last_used_at WHERE id = :id AND revoked_at IS NULL')->execute([
            'id' => $id,
            'token_hash' => $newHash,
            'expires_at' => Date::now()->modify(sprintf('+%d seconds', $ttl))->format('Y-m-d H:i:s'),
            'last_used_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function revokeByHash(string $tokenHash): void
    {
        Connection::get()->prepare('UPDATE user_sessions SET revoked_at = :revoked_at WHERE refresh_token_hash = :token_hash AND revoked_at IS NULL')->execute([
            'token_hash' => $tokenHash,
            'revoked_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function revokeAllForUser(int $userId): void
    {
        Connection::get()->prepare('UPDATE user_sessions SET revoked_at = :revoked_at WHERE user_id = :user_id AND revoked_at IS NULL')->execute([
            'user_id' => $userId,
            'revoked_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
