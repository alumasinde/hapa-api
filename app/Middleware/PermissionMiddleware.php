<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\RequestContext;
use App\Repository\AdminRepository;
use App\Support\Response;

final class PermissionMiddleware
{
    public function handle(string $permission, callable $next): mixed
    {
        $adminId = RequestContext::adminId();

        if (!$adminId || !in_array($permission, (new AdminRepository())->permissions($adminId), true)) {
            Response::error('FORBIDDEN', 'You do not have permission to perform this action', 403);
        }

        return $next();
    }
}
