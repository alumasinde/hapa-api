<?php

declare(strict_types=1);

namespace App\Security;

use App\Support\Date;
use App\Support\Env;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtService
{
    public function issue(int $userId, int $sessionId): string
    {
        $now = Date::now();
        $ttl = (int) Env::get('JWT_ACCESS_TTL', 900);

        return JWT::encode([
            'sub' => $userId,
            'sid' => $sessionId,
            'iat' => $now->getTimestamp(),
            'exp' => $now->modify(sprintf('+%d seconds', $ttl))->getTimestamp(),
        ], (string) Env::get('JWT_SECRET'), 'HS256');
    }

    public function claims(string $token): array
    {
        $payload = JWT::decode($token, new Key((string) Env::get('JWT_SECRET'), 'HS256'));

        return [
            'user_id' => (int) $payload->sub,
            'session_id' => isset($payload->sid) ? (int) $payload->sid : 0,
        ];
    }

    public function userId(string $token): int
    {
        return $this->claims($token)['user_id'];
    }
}
