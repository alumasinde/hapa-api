<?php

declare(strict_types=1);

namespace App\Security;

use App\Support\Date;
use App\Support\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class AdminJwtService
{
    public function issue(int $adminId): string
    {
        $now = Date::now();
        $ttl = (int) Env::get('ADMIN_JWT_ACCESS_TTL', 1800);

        return JWT::encode(['sub' => $adminId, 'type' => 'admin', 'iat' => $now->getTimestamp(), 'exp' => $now->modify(sprintf('+%d seconds', $ttl))->getTimestamp()], (string) Env::get('JWT_SECRET'), 'HS256');
    }

    public function adminId(string $token): int
    {
        $payload = JWT::decode($token, new Key((string) Env::get('JWT_SECRET'), 'HS256'));

        if (($payload->type ?? '') !== 'admin') {
            throw new \UnexpectedValueException('Invalid token type');
        }

        return (int) $payload->sub;
    }
}
