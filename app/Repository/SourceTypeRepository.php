<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;

final class SourceTypeRepository
{
    public function find(string $key): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM source_types WHERE source_key = :key LIMIT 1');
        $statement->execute(['key' => $key]);

        return $statement->fetch() ?: null;
    }

    public function keys(): array
    {
        return Connection::get()->query('SELECT source_key FROM source_types ORDER BY sort_order, id')->fetchAll();
    }
}
