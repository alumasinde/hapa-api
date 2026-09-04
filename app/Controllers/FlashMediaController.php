<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\FlashMediaRepository;
use App\Repository\FlashRepository;
use App\Security\RateLimiter;
use App\Support\Response;

final class FlashMediaController
{
    private const MAX_FILES = 6;
    private const MAX_FILE_SIZE = 8_000_000;
    private const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp'];

    public function __construct(
        private readonly FlashRepository $flashes = new FlashRepository(),
        private readonly FlashMediaRepository $media = new FlashMediaRepository(),
        private readonly RateLimiter $limits = new RateLimiter(),
    ) {
    }

    public function upload(string $id): never
    {
        $userId = RequestContext::userId();
        $flashId = $this->id($id);
        $flash = $this->flashes->find($flashId, null);

        if (!$flash) {
            Response::error('NOT_FOUND', 'Flash not found', 404);
        }
        if ($flash['reporter']['id'] !== $userId) {
            Response::error('FORBIDDEN', 'Only the flash owner can manage media', 403);
        }
        if (!$this->limits->allow('flash:media', (string) $userId, 20, 600)) {
            Response::error('RATE_LIMITED', 'Too many media uploads', 429);
        }

        $files = $_FILES['media'] ?? null;
        if (!$files) {
            Response::error('VALIDATION_ERROR', 'Media is required', 422, ['media' => 'Upload one or more images']);
        }

        $items = isset($files['name']) && is_array($files['name'])
            ? array_map(static fn (int $i): array => [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ], array_keys($files['name']))
            : [$files];

        if ($items === [] || count($items) > self::MAX_FILES || $this->media->countActive($flashId) + count($items) > self::MAX_FILES) {
            Response::error('VALIDATION_ERROR', 'A flash can have at most 6 images', 422, ['media' => 'Maximum of 6 images per flash']);
        }

        $created = [];
        foreach ($items as $file) {
            $created[] = $this->store($flashId, $file);
        }

        Response::json(['media' => $created], 201);
    }

    public function remove(string $flash, string $media): never
    {
        $userId = RequestContext::userId();
        $flashId = $this->id($flash);
        $mediaId = $this->id($media);
        $item = $this->media->find($mediaId);

        if (!$item || (int) $item['flash_id'] !== $flashId) {
            Response::error('NOT_FOUND', 'Media not found', 404);
        }

        $owner = $this->flashes->find($flashId, null);
        if (!$owner || $owner['reporter']['id'] !== $userId) {
            Response::error('FORBIDDEN', 'Only the flash owner can manage media', 403);
        }

        $removed = $this->media->remove($mediaId);
        if ($removed) {
            $path = dirname(__DIR__, 2) . '/public' . $removed['file_path'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        Response::json([], 204);
    }

    private function store(int $flashId, array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'] ?? '')) {
            Response::error('VALIDATION_ERROR', 'Image upload failed', 422, ['media' => 'Upload a valid image']);
        }
        if ((int) ($file['size'] ?? 0) < 1 || (int) $file['size'] > self::MAX_FILE_SIZE) {
            Response::error('VALIDATION_ERROR', 'Image is too large', 422, ['media' => 'Maximum file size is 8 MB']);
        }

        $info = @getimagesize($file['tmp_name']);
        $mime = $info['mime'] ?? '';
        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            Response::error('VALIDATION_ERROR', 'Image type is unsupported', 422, ['media' => 'Use JPEG, PNG, or WebP']);
        }

        $directory = dirname(__DIR__, 2) . '/public/uploads/flashes/' . $flashId;
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            Response::error('SERVER_ERROR', 'Unable to store image', 500);
        }

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            default => 'webp',
        };
        $name = bin2hex(random_bytes(16)) . '.' . $extension;
        $target = $directory . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            Response::error('SERVER_ERROR', 'Unable to store image', 500);
        }

        return $this->media->add(
            $flashId,
            '/uploads/flashes/' . $flashId . '/' . $name,
            $mime,
            (int) $file['size'],
            isset($info[0]) ? (int) $info[0] : null,
            isset($info[1]) ? (int) $info[1] : null,
        );
    }

    private function id(string $value): int
    {
        if (!ctype_digit($value) || (int) $value < 1) {
            Response::error('VALIDATION_ERROR', 'Resource id is invalid', 422);
        }

        return (int) $value;
    }
}
