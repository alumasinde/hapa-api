<?php

declare(strict_types=1);

namespace App\Security;

use App\Support\Date;
use App\Support\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class AdminJwtService
{
    public function issue(int $adminId, int $sessionId): string
    {
        $now = Date::now();
        $ttl = (int) Env::get('ADMIN_JWT_ACCESS_TTL', 1800);

        return JWT::encode(['sub' => $adminId, 'type' => 'admin', 'sid' => $sessionId, 'iat' => $now->getTimestamp(), 'exp' => $now->modify(sprintf('+%d seconds', $ttl))->getTimestamp()], (string) Env::get('JWT_SECRET'), 'HS256');
    }

    public function claims(string $token): array
    {
        $payload = JWT::decode($token, new Key((string) Env::get('JWT_SECRET'), 'HS256'));

        if (($payload->type ?? '') !== 'admin' || !isset($payload->sid)) {
            throw new \UnexpectedValueException('Invalid token type');
        }

        return ['admin_id' => (int) $payload->sub, 'session_id' => (int) $payload->sid];
    }

    public function adminId(string $token): int
    {
        return $this->claims($token)['admin_id'];
    }
}
