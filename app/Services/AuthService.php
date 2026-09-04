<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Security\JwtService;
use App\Security\PasswordHasher;
use App\Security\TokenService;
use App\Support\Env;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly SessionRepository $sessions = new SessionRepository(),
        private readonly PasswordHasher $hasher = new PasswordHasher(),
        private readonly JwtService $jwt = new JwtService(),
        private readonly TokenService $tokens = new TokenService(),
    ) {
    }

    public function register(array $data): array
    {
        $user = $this->users->create($data, $this->hasher->hash($data['password']));

        return $this->sessionPayload($user, $data['device_id'] ?? null, $data['platform'] ?? null);
    }

    public function login(string $login, string $password, ?string $deviceId, ?string $platform): ?array
    {
        $user = $this->users->findByLogin($login);

        if (!$user || $user['status'] !== 'active' || !$this->hasher->verify($password, $user['password_hash'])) {
            return null;
        }

        return $this->sessionPayload($user, $deviceId, $platform);
    }

    public function refresh(string $refreshToken): ?array
    {
        $hash = $this->tokens->hash($refreshToken);
        $session = $this->sessions->findActive($hash);

        if (!$session) {
            return null;
        }

        $user = $this->users->find((int) $session['user_id']);

        if (!$user || $user['status'] !== 'active') {
            return null;
        }

        $next = $this->tokens->refreshToken();
        $ttl = (int) Env::get('JWT_REFRESH_TTL', 2592000);
        $this->sessions->rotate((int) $session['id'], $this->tokens->hash($next), $ttl);

        return [
            'token' => $this->jwt->issue((int) $user['id'], (int) $session['id']),
            'refresh_token' => $next,
        ];
    }

    public function logout(string $refreshToken): void
    {
        $this->sessions->revokeByHash($this->tokens->hash($refreshToken));
    }

    private function sessionPayload(array $user, ?string $deviceId, ?string $platform): array
    {
        $refresh = $this->tokens->refreshToken();
        $sessionId = $this->sessions->create(
            (int) $user['id'],
            $this->tokens->hash($refresh),
            $deviceId,
            $platform,
            (int) Env::get('JWT_REFRESH_TTL', 2592000),
        );

        return [
            'user' => $this->publicUser($user),
            'token' => $this->jwt->issue((int) $user['id'], $sessionId),
            'refresh_token' => $refresh,
        ];
    }

    public function publicUser(array $user): array
    {
        return [
            'id' => (int) $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'display_name' => $user['display_name'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
        ];
    }
}
