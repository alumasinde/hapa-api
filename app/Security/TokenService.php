<?php

declare(strict_types=1);

namespace App\Security;

final class TokenService
{
    public function refreshToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '=');
    }

    public function hash(string $token): string
    {
        return hash('sha256', $token);
    }
}
