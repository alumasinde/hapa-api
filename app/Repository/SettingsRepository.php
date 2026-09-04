<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class SettingsRepository
{
    public function get(string $key, mixed $default = null): mixed
    {
        $statement = Connection::get()->prepare('SELECT setting_value FROM settings WHERE setting_key = :key LIMIT 1');
        $statement->execute(['key' => $key]);
        $value = $statement->fetchColumn();

        return $value === false ? $default : json_decode((string) $value, true, 512, JSON_THROW_ON_ERROR);
    }

    public function set(string $key, mixed $value): void
    {
        $now = Date::now()->format('Y-m-d H:i:s');
        Connection::get()->prepare('INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES (:key, :value, :now, :now) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = VALUES(updated_at)')->execute(['key' => $key, 'value' => json_encode($value, JSON_THROW_ON_ERROR), 'now' => $now]);
    }

    public function all(): array
    {
        $rows = Connection::get()->query('SELECT setting_key, setting_value, updated_at FROM settings ORDER BY setting_key')->fetchAll();
        return array_map(static fn(array $row): array => ['key' => $row['setting_key'], 'value' => json_decode($row['setting_value'], true), 'updated_at' => $row['updated_at']], $rows);
    }
}
