<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;
use PDO;

final class FlashRepository
{
    public function create(int $userId, int $categoryId, int $sourceTypeId, string $description, float $lat, float $lng, ?string $areaName, int $expiresAfterMinutes): array
    {
        $pdo = Connection::get();
        $now = Date::now();
        $expires = $now->modify(sprintf('+%d minutes', $expiresAfterMinutes));

        $statement = $pdo->prepare('INSERT INTO flashes (user_id, category_id, source_type_id, description, location, area_name, expires_at, created_at, updated_at) VALUES (:user_id, :category_id, :source_type_id, :description, ST_SRID(POINT(:lng, :lat), 4326), :area_name, :expires_at, :created_at, :updated_at)');
        $statement->execute([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'source_type_id' => $sourceTypeId,
            'description' => $description !== '' ? $description : null,
            'lat' => $lat,
            'lng' => $lng,
            'area_name' => $areaName !== '' ? $areaName : null,
            'expires_at' => $expires->format('Y-m-d H:i:s'),
            'created_at' => $now->format('Y-m-d H:i:s'),
            'updated_at' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->find((int) $pdo->lastInsertId(), null) ?? throw new \RuntimeException('Flash was not created');
    }

    public function find(int $id, ?array $origin): ?array
    {
        $sql = $this->selectSql($origin) . ' WHERE f.id = :id AND f.moderation_status = \'visible\' LIMIT 1';
        $statement = Connection::get()->prepare($sql);
        $params = ['id' => $id];
        if ($origin) {
            $params += $origin;
        }
        $statement->execute($params);

        return ($row = $statement->fetch()) ? $this->map($row) : null;
    }

    public function feed(float $lat, float $lng, float $radiusKm, array $categoryKeys, ?string $since, int $limit): array
    {
        $origin = ['lat' => $lat, 'lng' => $lng, 'radius_m' => $radiusKm * 1000];
        $sql = $this->selectSql($origin) . ' WHERE f.moderation_status = \'visible\' AND f.lifecycle_status = \'active\' AND f.expires_at > UTC_TIMESTAMP() AND ST_Distance_Sphere(f.location, POINT(:lng, :lat)) <= :radius_m';
        $params = $origin;

        if ($categoryKeys !== []) {
            $placeholders = [];
            foreach ($categoryKeys as $index => $key) {
                $name = 'category_' . $index;
                $placeholders[] = ':' . $name;
                $params[$name] = $key;
            }
            $sql .= ' AND c.category_key IN (' . implode(', ', $placeholders) . ')';
        }

        if ($since) {
            $sql .= ' AND f.created_at >= :since';
            $params['since'] = $since;
        }

        $sql .= ' ORDER BY distance_m ASC, f.created_at DESC LIMIT ' . $limit;
        $statement = Connection::get()->prepare($sql);
        $statement->execute($params);

        return array_map($this->map(...), $statement->fetchAll());
    }

    public function observe(int $flashId, int $userId, int $observationTypeId, ?string $note): ?array
    {
        $pdo = Connection::get();
        $now = Date::now()->format('Y-m-d H:i:s');
        $statement = $pdo->prepare('INSERT INTO flash_observations (flash_id, user_id, observation_type_id, note, created_at, updated_at) VALUES (:flash_id, :user_id, :observation_type_id, :note, :created_at, :updated_at) ON DUPLICATE KEY UPDATE note = VALUES(note), updated_at = VALUES(updated_at)');
        $statement->execute([
            'flash_id' => $flashId,
            'user_id' => $userId,
            'observation_type_id' => $observationTypeId,
            'note' => $note,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $this->find($flashId, null);
    }

    private function selectSql(?array $origin): string
    {
        $distance = $origin ? ', ST_Distance_Sphere(f.location, POINT(:lng, :lat)) AS distance_m' : ', NULL AS distance_m';

        return 'SELECT f.id, f.user_id, f.description, f.area_name, f.lifecycle_status, f.verification_state, f.moderation_status, f.expires_at, f.resolved_at, f.created_at, f.updated_at, ST_Y(f.location) AS lat, ST_X(f.location) AS lng' . $distance . ', c.category_key, c.name AS category_name, c.icon AS category_icon, u.display_name FROM flashes f JOIN categories c ON c.id = f.category_id JOIN users u ON u.id = f.user_id';
    }

    private function map(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'category' => ['key' => $row['category_key'], 'name' => $row['category_name'], 'icon' => $row['category_icon']],
            'status' => $row['lifecycle_status'],
            'verification_state' => $row['verification_state'],
            'description' => $row['description'],
            'location' => ['lat' => (float) $row['lat'], 'lng' => (float) $row['lng'], 'area_name' => $row['area_name']],
            'distance_km' => $row['distance_m'] === null ? null : round(((float) $row['distance_m']) / 1000, 2),
            'reporter' => ['id' => (int) $row['user_id'], 'display_name' => $row['display_name']],
            'created_at' => $row['created_at'],
            'expires_at' => $row['expires_at'],
        ];
    }
}
