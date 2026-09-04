<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Support\Env;
use App\Support\Response;

final class CorsMiddleware
{
    public function handle(callable $next): mixed
    {
        $allowed = array_values(array_filter(array_map('trim', explode(',', (string) Env::get('CORS_ALLOWED_ORIGINS', '')))));
        $origin = (string) ($_SERVER['HTTP_ORIGIN'] ?? '');

        if ($origin !== '' && ($allowed === ['*'] || in_array($origin, $allowed, true))) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Vary: Origin');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, Idempotency-Key, X-Request-Id');
            header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
            header('Access-Control-Max-Age: 600');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        return $next();
    }
}
