<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;

final class CategoryRepository
{
    public function enabled(): array
    {
        return Connection::get()->query('SELECT id, category_key, name, description, icon, expires_after_minutes FROM categories WHERE is_enabled = 1 ORDER BY sort_order, id')->fetchAll();
    }

    public function findEnabled(string $key): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM categories WHERE category_key = :key AND is_enabled = 1 LIMIT 1');
        $statement->execute(['key' => $key]);

        return $statement->fetch() ?: null;
    }
}
