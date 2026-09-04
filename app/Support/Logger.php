<?php
declare(strict_types=1);
namespace App\Support;

use App\Core\RequestContext;

final class Logger
{
    public static function info(string $event, array $context = []): void { self::write('info', $event, $context); }
    public static function error(string $event, array $context = []): void { self::write('error', $event, $context); }

    private static function write(string $level, string $event, array $context): void
    {
        $dir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $record = [
            'timestamp' => gmdate('c'),
            'level' => $level,
            'event' => $event,
            'request_id' => RequestContext::requestId(),
            'context' => $context,
        ];
        @file_put_contents($dir . '/app.log', json_encode($record, JSON_UNESCAPED_SLASHES) . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}