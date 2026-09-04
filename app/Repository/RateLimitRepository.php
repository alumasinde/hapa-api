<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class RateLimitRepository
{
    public function hit(string $scope, string $identifier, int $windowSeconds): int
    {
        Connection::get()->prepare('DELETE FROM rate_limits WHERE expires_at <= UTC_TIMESTAMP()')->execute();

        $statement = Connection::get()->prepare('SELECT * FROM rate_limits WHERE scope_key = :scope AND identifier = :identifier LIMIT 1');
        $statement->execute(['scope' => $scope, 'identifier' => $identifier]);
        $row = $statement->fetch();

        if (!$row) {
            Connection::get()->prepare('INSERT INTO rate_limits (scope_key, identifier, attempts, expires_at, created_at, updated_at) VALUES (:scope, :identifier, 1, :expires_at, :created_at, :updated_at)')->execute([
                'scope' => $scope,
                'identifier' => $identifier,
                'expires_at' => Date::now()->modify(sprintf('+%d seconds', $windowSeconds))->format('Y-m-d H:i:s'),
                'created_at' => Date::now()->format('Y-m-d H:i:s'),
                'updated_at' => Date::now()->format('Y-m-d H:i:s'),
            ]);

            return 1;
        }

        Connection::get()->prepare('UPDATE rate_limits SET attempts = attempts + 1, updated_at = :updated_at WHERE id = :id')->execute([
            'id' => $row['id'],
            'updated_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);

        return (int) $row['attempts'] + 1;
    }
}
