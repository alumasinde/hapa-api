<?php

declare(strict_types=1);

namespace App\Security;

use App\Support\Date;
use App\Support\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtService
{
    public function issue(int $userId): string
    {
        $now = Date::now();
        $ttl = (int) Env::get('JWT_ACCESS_TTL', 900);

        return JWT::encode([
            'sub' => $userId,
            'iat' => $now->getTimestamp(),
            'exp' => $now->modify(sprintf('+%d seconds', $ttl))->getTimestamp(),
        ], (string) Env::get('JWT_SECRET'), 'HS256');
    }

    public function userId(string $token): int
    {
        $payload = JWT::decode($token, new Key((string) Env::get('JWT_SECRET'), 'HS256'));

        return (int) $payload->sub;
    }
}
