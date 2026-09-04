<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\CategoryRepository;
use App\Repository\FlashRepository;
use App\Repository\ObservationTypeRepository;
use App\Repository\SourceTypeRepository;
use App\Security\RateLimiter;
use App\Support\Request;
use App\Support\Response;

final class FlashController
{
    public function __construct(
        private readonly FlashRepository $flashes = new FlashRepository(),
        private readonly CategoryRepository $categories = new CategoryRepository(),
        private readonly SourceTypeRepository $sources = new SourceTypeRepository(),
        private readonly ObservationTypeRepository $observations = new ObservationTypeRepository(),
        private readonly RateLimiter $limits = new RateLimiter(),
    ) {
    }

    public function index(): never
    {
        $lat = $this->number($_GET['lat'] ?? null, 'lat', -90, 90);
        $lng = $this->number($_GET['lng'] ?? null, 'lng', -180, 180);
        $radius = $this->number($_GET['radius'] ?? 5, 'radius', 0.1, 20);
        $limit = (int) ($_GET['limit'] ?? 30);
        $limit = max(1, min(50, $limit));
        $categories = array_values(array_filter(array_map('trim', explode(',', (string) ($_GET['category'] ?? '')))));
        $since = isset($_GET['since']) && $_GET['since'] !== '' ? (string) $_GET['since'] : null;

        if ($since !== null && strtotime($since) === false) {
            Response::error('VALIDATION_ERROR', 'since must be a valid date/time', 422, ['since' => 'Enter a valid ISO 8601 date/time']);
        }

        Response::json(['flashes' => $this->flashes->feed($lat, $lng, $radius, $categories, $since, $limit)]);
    }

    public function show(string $id): never
    {
        $flashId = $this->positiveId($id);
        $flash = $this->flashes->find($flashId, null);
        if (!$flash) {
            Response::error('NOT_FOUND', 'Flash not found', 404);
        }

        Response::json($flash);
    }

    public function create(): never
    {
        $userId = RequestContext::userId();
        if (!$userId) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        $data = Request::json();
        $categoryKey = trim((string) ($data['category'] ?? ''));
        $sourceKey = trim((string) ($data['source'] ?? 'user'));
        $description = trim((string) ($data['description'] ?? ''));
        $areaName = trim((string) ($data['area_name'] ?? ''));
        $lat = $this->number($data['lat'] ?? null, 'lat', -90, 90);
        $lng = $this->number($data['lng'] ?? null, 'lng', -180, 180);

        if ($categoryKey === '') {
            Response::error('VALIDATION_ERROR', 'Category is required', 422, ['category' => 'This field is required']);
        }
        if (mb_strlen($description) > 500) {
            Response::error('VALIDATION_ERROR', 'Description is too long', 422, ['description' => 'Maximum length is 500 characters']);
        }
        if (mb_strlen($areaName) > 191) {
            Response::error('VALIDATION_ERROR', 'Area name is too long', 422, ['area_name' => 'Maximum length is 191 characters']);
        }

        $category = $this->categories->findEnabled($categoryKey);
        if (!$category) {
            Response::error('VALIDATION_ERROR', 'Category is unavailable', 422, ['category' => 'This category is unavailable']);
        }
        $source = $this->sources->find($sourceKey);
        if (!$source) {
            Response::error('VALIDATION_ERROR', 'Source is invalid', 422, ['source' => 'This source is invalid']);
        }
        if (!$this->limits->allow('flash:create', (string) $userId, 5, 600)) {
            Response::error('RATE_LIMITED', 'Too many flash reports', 429);
        }

        Response::json($this->flashes->create($userId, (int) $category['id'], (int) $source['id'], $description, $lat, $lng, $areaName ?: null, (int) $category['expires_after_minutes']), 201);
    }

    public function observe(string $id): never
    {
        $userId = RequestContext::userId();
        if (!$userId) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        $flashId = $this->positiveId($id);
        $data = Request::json();
        $key = trim((string) ($data['observation'] ?? ''));
        $note = trim((string) ($data['note'] ?? ''));

        if ($key === '') {
            Response::error('VALIDATION_ERROR', 'Observation is required', 422, ['observation' => 'This field is required']);
        }
        if (mb_strlen($note) > 300) {
            Response::error('VALIDATION_ERROR', 'Observation note is too long', 422, ['note' => 'Maximum length is 300 characters']);
        }

        $flash = $this->flashes->find($flashId, null);
        if (!$flash) {
            Response::error('NOT_FOUND', 'Flash not found', 404);
        }
        if (!$this->flashes->canObserve($flashId, $userId)) {
            Response::error('FORBIDDEN', 'You cannot observe your own, inactive, expired, or unavailable flash', 403);
        }

        $type = $this->observations->findForCategory($flash['category']['key'], $key);
        if (!$type) {
            Response::error('VALIDATION_ERROR', 'Observation is unavailable for this category', 422, ['observation' => 'This observation is unavailable']);
        }
        if (!$this->limits->allow('flash:observe', (string) $userId, 20, 600)) {
            Response::error('RATE_LIMITED', 'Too many observations', 429);
        }

        Response::json($this->flashes->observe($flashId, $userId, (int) $type['id'], $note !== '' ? $note : null));
    }

    private function positiveId(string $value): int
    {
        if (!ctype_digit($value) || (int) $value < 1) {
            Response::error('VALIDATION_ERROR', 'Resource id is invalid', 422, ['id' => 'Enter a positive integer']);
        }

        return (int) $value;
    }

    private function number(mixed $value, string $field, float $min, float $max): float
    {
        if (!is_numeric($value) || !is_finite((float) $value) || (float) $value < $min || (float) $value > $max) {
            Response::error('VALIDATION_ERROR', $field . ' is invalid', 422, [$field => 'Enter a valid value']);
        }

        return (float) $value;
    }
}
