<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\RateLimitRepository;

final class RateLimiter
{
    public function __construct(private readonly RateLimitRepository $limits = new RateLimitRepository())
    {
    }

    public function allow(string $scope, string $identifier, int $maxAttempts, int $windowSeconds): bool
    {
        return $this->limits->hit($scope, $identifier, $windowSeconds) <= $maxAttempts;
    }
}
