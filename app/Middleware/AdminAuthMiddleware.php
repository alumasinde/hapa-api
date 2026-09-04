<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\RequestContext;
use App\Repository\AdminRepository;
use App\Security\AdminJwtService;
use App\Support\Response;

final class AdminAuthMiddleware
{
    public function handle(callable $next): mixed
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            Response::error('UNAUTHORIZED', 'Admin authentication token is required', 401);
        }

        try {
            $claims = (new AdminJwtService())->claims($matches[1]);
            $adminId = $claims['admin_id'];
            $sessions = new AdminSessionRepository();
            if (!$sessions->active($claims['session_id'], $adminId)) {
                Response::error('UNAUTHORIZED', 'Admin session is unavailable', 401);
            }
            $sessions->touch($claims['session_id']);
            $admin = (new AdminRepository())->find($adminId);
            if (!$admin || $admin['status'] !== 'active') {
                Response::error('UNAUTHORIZED', 'Admin is unavailable', 401);
            }
            RequestContext::setAdminId($adminId);
            return $next();
        } catch (\Throwable) {
            Response::error('UNAUTHORIZED', 'Admin authentication token is invalid', 401);
        }
    }
}
