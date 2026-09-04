<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\RequestContext;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Security\JwtService;
use App\Support\Request;
use App\Support\Response;

final class AuthMiddleware
{
    public function __construct(
        private readonly JwtService $jwt = new JwtService(),
        private readonly UserRepository $users = new UserRepository(),
        private readonly SessionRepository $sessions = new SessionRepository(),
    ) {
    }

    public function handle(callable $next): mixed
    {
        $header = Request::header('Authorization');

        if (!$header || !preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        try {
            $claims = $this->jwt->claims($matches[1]);
        } catch (\Throwable) {
            Response::error('UNAUTHORIZED', 'Authentication token is invalid', 401);
        }

        if ($claims['session_id'] < 1 || !$this->sessions->isActive($claims['session_id'], $claims['user_id'])) {
            Response::error('UNAUTHORIZED', 'Authentication session is no longer active', 401);
        }

        $user = $this->users->find($claims['user_id']);

        if (!$user || $user['status'] !== 'active') {
            Response::error('UNAUTHORIZED', 'User is unavailable', 401);
        }

        RequestContext::setUserId($claims['user_id']);

        return $next();
    }
}
