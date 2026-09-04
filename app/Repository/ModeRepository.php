<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;

final class ModeRepository
{
    public function active(): array
    {
        return Connection::get()->query('SELECT mode_key, name, description, starts_at, ends_at, priority FROM modes WHERE is_enabled = 1 AND (starts_at IS NULL OR starts_at <= UTC_TIMESTAMP()) AND (ends_at IS NULL OR ends_at >= UTC_TIMESTAMP()) ORDER BY priority DESC, id')->fetchAll();
    }
}
