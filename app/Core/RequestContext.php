<?php

declare(strict_types=1);

namespace App\Core;

final class RequestContext
{
    private static ?int $userId = null;
    private static string $requestId = '';

    public static function setUserId(int $userId): void
    {
        self::$userId = $userId;
    }

    public static function userId(): ?int
    {
        return self::$userId;
    }

    public static function setRequestId(string $requestId): void
    {
        self::$requestId = $requestId;
    }

    public static function requestId(): string
    {
        return self::$requestId;
    }
}
