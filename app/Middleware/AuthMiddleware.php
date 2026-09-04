<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\RequestContext;
use App\Repository\UserRepository;
use App\Security\JwtService;
use App\Support\Request;
use App\Support\Response;

final class AuthMiddleware
{
    public function __construct(
        private readonly JwtService $jwt = new JwtService(),
        private readonly UserRepository $users = new UserRepository(),
    ) {
    }

    public function handle(callable $next): mixed
    {
        $header = Request::header('Authorization');

        if (!$header || !preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        try {
            $userId = $this->jwt->userId($matches[1]);
        } catch (\Throwable) {
            Response::error('UNAUTHORIZED', 'Authentication token is invalid', 401);
        }

        $user = $this->users->find($userId);

        if (!$user || $user['status'] !== 'active') {
            Response::error('UNAUTHORIZED', 'User is unavailable', 401);
        }

        RequestContext::setUserId($userId);

        return $next();
    }
}
