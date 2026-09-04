<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class FlashMediaRepository
{
    public function countActive(int $flashId): int
    {
        $statement = Connection::get()->prepare('SELECT COUNT(*) FROM flash_media WHERE flash_id = :flash_id AND deleted_at IS NULL');
        $statement->execute(['flash_id' => $flashId]);

        return (int) $statement->fetchColumn();
    }

    public function add(int $flashId, string $path, string $mimeType, int $fileSize, ?int $width, ?int $height): array
    {
        $pdo = Connection::get();
        $now = Date::now()->format('Y-m-d H:i:s');
        $sort = $this->countActive($flashId);

        $statement = $pdo->prepare('INSERT INTO flash_media (flash_id, media_type, file_path, sort_order, width, height, file_size, mime_type, created_at) VALUES (:flash_id, :media_type, :file_path, :sort_order, :width, :height, :file_size, :mime_type, :created_at)');
        $statement->execute([
            'flash_id' => $flashId,
            'media_type' => 'image',
            'file_path' => $path,
            'sort_order' => $sort,
            'width' => $width,
            'height' => $height,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'created_at' => $now,
        ]);

        return $this->find((int) $pdo->lastInsertId()) ?? throw new \RuntimeException('Media was not created');
    }

    public function find(int $id): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM flash_media WHERE id = :id AND deleted_at IS NULL LIMIT 1');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function remove(int $id): ?array
    {
        $media = $this->find($id);
        if (!$media) {
            return null;
        }

        Connection::get()->prepare('UPDATE flash_media SET deleted_at = :deleted_at WHERE id = :id')->execute([
            'id' => $id,
            'deleted_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);

        return $media;
    }

    public function forFlash(int $flashId): array
    {
        $statement = Connection::get()->prepare('SELECT id, media_type, file_path, thumbnail_path, sort_order, width, height, file_size, mime_type, created_at FROM flash_media WHERE flash_id = :flash_id AND deleted_at IS NULL ORDER BY sort_order ASC, id ASC');
        $statement->execute(['flash_id' => $flashId]);

        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'type' => $row['media_type'],
            'path' => $row['file_path'],
            'thumbnail_path' => $row['thumbnail_path'],
            'sort_order' => (int) $row['sort_order'],
            'width' => $row['width'] === null ? null : (int) $row['width'],
            'height' => $row['height'] === null ? null : (int) $row['height'],
            'file_size' => $row['file_size'] === null ? null : (int) $row['file_size'],
            'mime_type' => $row['mime_type'],
            'created_at' => $row['created_at'],
        ], $statement->fetchAll());
    }
}
