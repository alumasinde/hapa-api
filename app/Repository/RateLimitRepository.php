<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class RateLimitRepository
{
    public function hit(string $scope, string $identifier, int $windowSeconds): array
    {
        $pdo = Connection::get();

        $now = Date::now();
        $nowValue = $now->format('Y-m-d H:i:s');
        $expiresValue = $now
            ->modify(sprintf('+%d seconds', $windowSeconds))
            ->format('Y-m-d H:i:s');

        $pdo->prepare(
            'DELETE FROM rate_limits WHERE expires_at <= :cleanup_now'
        )->execute([
            'cleanup_now' => $nowValue,
        ]);

        /*
         * PDO native prepares do not safely support reusing the same named
         * placeholder multiple times. MariaDB is running with native prepares
         * enabled, so every occurrence below intentionally has its own name.
         */
        $statement = $pdo->prepare(
            'INSERT INTO rate_limits (
                scope_key,
                identifier,
                attempts,
                expires_at,
                created_at,
                updated_at
            ) VALUES (
                :insert_scope,
                :insert_identifier,
                1,
                :insert_expires_at,
                :insert_created_at,
                :insert_updated_at
            )
            ON DUPLICATE KEY UPDATE
                attempts = IF(expires_at <= :reset_check_now, 1, attempts + 1),
                expires_at = IF(
                    expires_at <= :reset_expiry_now,
                    :reset_expires_at,
                    expires_at
                ),
                updated_at = :update_updated_at'
        );

        $statement->execute([
            'insert_scope' => $scope,
            'insert_identifier' => $identifier,
            'insert_expires_at' => $expiresValue,
            'insert_created_at' => $nowValue,
            'insert_updated_at' => $nowValue,
            'reset_check_now' => $nowValue,
            'reset_expiry_now' => $nowValue,
            'reset_expires_at' => $expiresValue,
            'update_updated_at' => $nowValue,
        ]);

        $read = $pdo->prepare(
            'SELECT attempts, expires_at
             FROM rate_limits
             WHERE scope_key = :read_scope
               AND identifier = :read_identifier
             LIMIT 1'
        );

        $read->execute([
            'read_scope' => $scope,
            'read_identifier' => $identifier,
        ]);

        $row = $read->fetch();

        if ($row === false) {
            throw new \RuntimeException('Rate limit state could not be read');
        }

        return [
            'attempts' => (int) $row['attempts'],
            'expires_at' => (string) $row['expires_at'],
        ];
    }
}
