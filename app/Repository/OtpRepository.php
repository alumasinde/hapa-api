<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class OtpRepository
{
    public function invalidateActive(string $destination, string $purpose): void
    {
        Connection::get()->prepare('UPDATE otps SET consumed_at = :consumed_at WHERE destination = :destination AND purpose = :purpose AND consumed_at IS NULL AND expires_at > UTC_TIMESTAMP()')->execute([
            'destination' => $destination,
            'purpose' => $purpose,
            'consumed_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function create(?int $userId, string $destination, string $purpose, string $codeHash, int $ttl): void
    {
        Connection::get()->prepare('INSERT INTO otps (user_id, destination, purpose, code_hash, expires_at, created_at) VALUES (:user_id, :destination, :purpose, :code_hash, :expires_at, :created_at)')->execute([
            'user_id' => $userId,
            'destination' => $destination,
            'purpose' => $purpose,
            'code_hash' => $codeHash,
            'expires_at' => Date::now()->modify(sprintf('+%d seconds', $ttl))->format('Y-m-d H:i:s'),
            'created_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function latest(string $destination, string $purpose): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM otps WHERE destination = :destination AND purpose = :purpose AND consumed_at IS NULL AND expires_at > UTC_TIMESTAMP() ORDER BY id DESC LIMIT 1');
        $statement->execute(['destination' => $destination, 'purpose' => $purpose]);

        return $statement->fetch() ?: null;
    }

    public function incrementAttempts(int $id): void
    {
        Connection::get()->prepare('UPDATE otps SET attempts = attempts + 1 WHERE id = :id AND consumed_at IS NULL')->execute(['id' => $id]);
    }

    public function consume(int $id): void
    {
        Connection::get()->prepare('UPDATE otps SET consumed_at = :consumed_at WHERE id = :id AND consumed_at IS NULL')->execute([
            'id' => $id,
            'consumed_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
