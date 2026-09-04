<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\FlashEngagementRepository;
use App\Repository\FlashRepository;
use App\Security\RateLimiter;
use App\Support\Request;
use App\Support\Response;

final class FlashEngagementController
{
    public function __construct(
        private readonly FlashRepository $flashes = new FlashRepository(),
        private readonly FlashEngagementRepository $engagement = new FlashEngagementRepository(),
        private readonly RateLimiter $limits = new RateLimiter(),
    ) {
    }

    public function helpful(string $id): never
    {
        $userId = $this->userId();
        $flashId = $this->flashId($id);
        $this->ensureFlashExists($flashId);

        if (!$this->limits->allow('flash:helpful', (string) $userId, 30, 600)) {
            Response::error('RATE_LIMITED', 'Too many engagement actions', 429);
        }

        $this->engagement->markHelpful($flashId, $userId);
        Response::json(['engagement' => $this->engagement->stats($flashId, $userId)]);
    }

    public function removeHelpful(string $id): never
    {
        $userId = $this->userId();
        $flashId = $this->flashId($id);
        $this->ensureFlashExists($flashId);

        $this->engagement->removeHelpful($flashId, $userId);
        Response::json(['engagement' => $this->engagement->stats($flashId, $userId)]);
    }

    public function share(string $id): never
    {
        $userId = $this->userId();
        $flashId = $this->flashId($id);
        $this->ensureFlashExists($flashId);

        if (!$this->limits->allow('flash:share', (string) $userId, 30, 600)) {
            Response::error('RATE_LIMITED', 'Too many share actions', 429);
        }

        $this->engagement->recordShare($flashId, $userId);
        Response::json(['engagement' => $this->engagement->stats($flashId, $userId)]);
    }

    private function userId(): int
    {
        $userId = RequestContext::userId();
        if (!$userId) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        return $userId;
    }

    private function flashId(string $value): int
    {
        if (!ctype_digit($value) || (int) $value < 1) {
            Response::error('VALIDATION_ERROR', 'Resource id is invalid', 422, ['id' => 'Enter a positive integer']);
        }

        return (int) $value;
    }

    private function ensureFlashExists(int $flashId): void
    {
        if (!$this->flashes->find($flashId, null)) {
            Response::error('NOT_FOUND', 'Flash not found', 404);
        }
    }
}
