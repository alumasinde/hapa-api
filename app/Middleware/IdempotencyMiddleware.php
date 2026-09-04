<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\RequestContext;
use App\Repository\IdempotencyRepository;
use App\Support\Request;
use App\Support\Response;

final class IdempotencyMiddleware
{
    public function __construct(private readonly IdempotencyRepository $keys = new IdempotencyRepository())
    {
    }

    public function handle(string $routeKey, callable $next): mixed
    {
        $userId = RequestContext::userId();
        $key = Request::header('Idempotency-Key');

        if (!$userId || !$key || !preg_match('/^[A-Za-z0-9_-]{8,128}$/', $key)) {
            Response::error('VALIDATION_ERROR', 'A valid Idempotency-Key header is required', 422);
        }

        if ($this->keys->find($userId, $routeKey, $key)) {
            Response::error('IDEMPOTENCY_REPLAY', 'This request was already processed', 409);
        }

        $this->keys->create($userId, $routeKey, $key, 86400);

        return $next();
    }
}
