<?php

declare(strict_types=1);

namespace App\Support;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class Date
{
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

 public static function iso(DateTimeImmutable $date): string
{
    return $date->setTimezone(new DateTimeZone('UTC'))->format(DateTimeInterface::ATOM);
}
    public static function expiresInMinutes(int $minutes): DateTimeImmutable
    {
        return self::now()->modify(sprintf('+%d minutes', $minutes));
    }
}
