<?php

declare(strict_types=1);

namespace App\Core;

use App\Support\Env;
use App\Support\Response;

final class App
{
    public static function boot(string $basePath): self
    {
        Env::load($basePath . '/.env');
        date_default_timezone_set((string) Env::get('APP_TIMEZONE', 'UTC'));

        return new self($basePath);
    }

    public function __construct(private readonly string $basePath)
    {
    }

    public function run(): void
    {
        $router = new Router();
        $register = require $this->basePath . '/routes/api.php';
        $register($router);

        $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
        $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

        if ($router->dispatch($method, $path)) {
            return;
        }

        Response::error('NOT_FOUND', 'Endpoint not found', 404);
    }
}
