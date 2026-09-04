<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\OtpRepository;

final class OtpService
{
    public function __construct(
        private readonly OtpRepository $otps = new OtpRepository(),
        private readonly PasswordHasher $hasher = new PasswordHasher(),
    ) {
    }

    public function generate(?int $userId, string $destination, string $purpose): string
    {
        $code = (string) random_int(100000, 999999);
        $this->otps->create($userId, $destination, $purpose, $this->hasher->hash($code), 600);

        return $code;
    }

    public function verify(string $destination, string $purpose, string $code): bool
    {
        $otp = $this->otps->latest($destination, $purpose);

        if (!$otp || (int) $otp['attempts'] >= 5) {
            return false;
        }

        if (!$this->hasher->verify($code, $otp['code_hash'])) {
            $this->otps->incrementAttempts((int) $otp['id']);

            return false;
        }

        $this->otps->consume((int) $otp['id']);

        return true;
    }
}
