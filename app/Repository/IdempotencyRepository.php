<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class IdempotencyRepository
{
    public function find(int $userId, string $routeKey, string $key): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM idempotency_keys WHERE user_id = :user_id AND route_key = :route_key AND idempotency_key = :idempotency_key AND expires_at > UTC_TIMESTAMP() LIMIT 1');
        $statement->execute([
            'user_id' => $userId,
            'route_key' => $routeKey,
            'idempotency_key' => $key,
        ]);

        return $statement->fetch() ?: null;
    }

    public function create(int $userId, string $routeKey, string $key, int $ttl): void
    {
        $now = Date::now();
        Connection::get()->prepare('INSERT INTO idempotency_keys (user_id, route_key, idempotency_key, expires_at, created_at) VALUES (:user_id, :route_key, :idempotency_key, :expires_at, :created_at)')->execute([
            'user_id' => $userId,
            'route_key' => $routeKey,
            'idempotency_key' => $key,
            'expires_at' => $now->modify(sprintf('+%d seconds', $ttl))->format('Y-m-d H:i:s'),
            'created_at' => $now->format('Y-m-d H:i:s'),
        ]);
    }
}
