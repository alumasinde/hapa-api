<?php

declare(strict_types=1);

namespace App\Support;

final class Response
{
    public static function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

        exit;
    }

    public static function error(string $code, string $message, int $status, array $fields = []): never
    {
        $error = ['code' => $code, 'message' => $message];

        if ($fields !== []) {
            $error['fields'] = $fields;
        }

        self::json(['error' => $error], $status);
    }
}
