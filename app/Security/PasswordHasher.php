<?php

declare(strict_types=1);

namespace App\Security;

final class PasswordHasher
{
    public function hash(string $value): string
    {
        return password_hash($value, PASSWORD_ARGON2ID);
    }

    public function verify(string $value, string $hash): bool
    {
        return password_verify($value, $hash);
    }
}
