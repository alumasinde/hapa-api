<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class FlashRepository
{
    private FlashMediaRepository $media;

    public function __construct()
    {
        $this->media = new FlashMediaRepository();
    }
    public function create(int $userId, int $categoryId, int $sourceTypeId, string $description, float $lat, float $lng, ?string $areaName, int $expiresAfterMinutes): array
    {
        $pdo = Connection::get();
        $now = Date::now();
        $expires = $now->modify(sprintf('+%d minutes', $expiresAfterMinutes));

        $statement = $pdo->prepare('INSERT INTO flashes (user_id, category_id, source_type_id, description, location, area_name, expires_at, created_at, updated_at) VALUES (:user_id, :category_id, :source_type_id, :description, ST_GeomFromText(CONCAT(\'POINT(\', :lng, \' \', :lat, \')\'), 4326), :area_name, :expires_at, :created_at, :updated_at)');
        $statement->execute([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'source_type_id' => $sourceTypeId,
            'description' => $description !== '' ? $description : null,
            'lat' => $lat,
            'lng' => $lng,
            'area_name' => $areaName,
            'expires_at' => $expires->format('Y-m-d H:i:s'),
            'created_at' => $now->format('Y-m-d H:i:s'),
            'updated_at' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->find((int) $pdo->lastInsertId(), null) ?? throw new \RuntimeException('Flash was not created');
    }

    public function find(int $id, ?array $origin): ?array
    {
        $sql = $this->selectSql($origin) . ' WHERE f.id = :id AND f.moderation_status = \'visible\' GROUP BY f.id LIMIT 1';
        $statement = Connection::get()->prepare($sql);
        $params = ['id' => $id];

        if ($origin) {
            $params += $origin;
        }

        $statement->execute($params);

        return ($row = $statement->fetch()) ? $this->map($row) : null;
    }

    public function canObserve(int $flashId): bool
    {
        $statement = Connection::get()->prepare('SELECT 1 FROM flashes WHERE id = :id AND moderation_status = \'visible\' AND lifecycle_status = \'active\' AND expires_at > UTC_TIMESTAMP() LIMIT 1');
        $statement->execute(['id' => $flashId]);

        return (bool) $statement->fetchColumn();
    }

    public function feed(float $lat, float $lng, float $radiusKm, array $categoryKeys, ?string $since, int $limit): array
    {
        $origin = [
            'origin_lat' => $lat,
            'origin_lng' => $lng,
        ];
        $params = [
            ...$origin,
            'filter_lat' => $lat,
            'filter_lng' => $lng,
            'radius_m' => $radiusKm * 1000,
        ];

        $sql = $this->selectSql($origin) . ' WHERE f.moderation_status = \'visible\' AND f.lifecycle_status = \'active\' AND f.expires_at > UTC_TIMESTAMP() AND ST_Distance_Sphere(f.location, POINT(:filter_lng, :filter_lat)) <= :radius_m';

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
            $timestamp = strtotime($since);
            if ($timestamp === false) {
                throw new \InvalidArgumentException('Invalid since value');
            }
            $sql .= ' AND f.created_at >= :since';
            $params['since'] = gmdate('Y-m-d H:i:s', $timestamp);
        }

        $sql .= ' GROUP BY f.id ORDER BY distance_m ASC, f.created_at DESC LIMIT ' . $limit;
        $statement = Connection::get()->prepare($sql);
        $statement->execute($params);

        return array_map($this->map(...), $statement->fetchAll());
    }

    public function observe(int $flashId, int $userId, int $observationTypeId, ?string $note): ?array
    {
        $now = Date::now()->format('Y-m-d H:i:s');
        $statement = Connection::get()->prepare('INSERT INTO flash_observations (flash_id, user_id, observation_type_id, note, created_at, updated_at) VALUES (:flash_id, :user_id, :observation_type_id, :note, :created_at, :updated_at) ON DUPLICATE KEY UPDATE observation_type_id = VALUES(observation_type_id), note = VALUES(note), updated_at = VALUES(updated_at)');
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
        $distance = $origin
            ? ', ST_Distance_Sphere(f.location, POINT(:origin_lng, :origin_lat)) AS distance_m'
            : ', NULL AS distance_m';

        return 'SELECT f.id, f.user_id, f.description, f.area_name, f.lifecycle_status, f.verification_state, f.moderation_status, f.expires_at, f.resolved_at, f.created_at, f.updated_at, ST_Y(f.location) AS lat, ST_X(f.location) AS lng' . $distance . ', c.category_key, c.name AS category_name, c.icon AS category_icon, u.display_name, COALESCE(SUM(ot.observation_key = \'still_happening\'), 0) AS confirm_count, COALESCE(SUM(ot.observation_key = \'cleared\'), 0) AS dispute_count FROM flashes f JOIN categories c ON c.id = f.category_id JOIN users u ON u.id = f.user_id LEFT JOIN flash_observations fo ON fo.flash_id = f.id LEFT JOIN observation_types ot ON ot.id = fo.observation_type_id';
    }

    private function map(array $row): array
    {
        $status = $row['lifecycle_status'];
        if ($status === 'active' && strtotime($row['expires_at'] . ' UTC') <= time()) {
            $status = 'expired';
        }

        return [
            'id' => (int) $row['id'],
            'category' => ['key' => $row['category_key'], 'name' => $row['category_name'], 'icon' => $row['category_icon']],
            'status' => $status,
            'verification_state' => $row['verification_state'],
            'description' => $row['description'],
            'location' => ['lat' => (float) $row['lat'], 'lng' => (float) $row['lng'], 'area_name' => $row['area_name']],
            'distance_km' => $row['distance_m'] === null ? null : round(((float) $row['distance_m']) / 1000, 2),
            'reporter' => ['id' => (int) $row['user_id'], 'display_name' => $row['display_name']],
            'confirm_count' => (int) $row['confirm_count'],
            'dispute_count' => (int) $row['dispute_count'],
            'created_at' => $row['created_at'],
            'expires_at' => $row['expires_at'],
            'media' => $this->media->forFlash((int) $row['id']),
        ];
    }
}
