<?php

declare(strict_types=1);

namespace App\Support;

final class Request
{
    public static function json(): array
    {
        $body = file_get_contents('php://input');

        if ($body === false || trim($body) === '') {
            return [];
        }

        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        return is_array($data) ? $data : [];
    }

    public static function query(string $key): ?string
    {
        return isset($_GET[$key]) ? (string) $_GET[$key] : null;
    }

    public static function header(string $name): ?string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        return $_SERVER[$key] ?? null;
    }

    public static function ip(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
    }
}
