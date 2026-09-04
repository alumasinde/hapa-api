<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\RequestContext;
use App\Support\Request;

final class RequestIdMiddleware
{
    public function handle(callable $next): mixed
    {
        $requestId = Request::header('X-Request-Id');

        if (!$requestId || !preg_match('/^[A-Za-z0-9_-]{8,100}$/', $requestId)) {
            $requestId = bin2hex(random_bytes(16));
        }

        RequestContext::setRequestId($requestId);
        header('X-Request-Id: ' . $requestId);

        return $next();
    }
}
