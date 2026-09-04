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

        return new self();
    }

    public function run(): void
    {
        Response::json([
            'data' => [
                'name' => 'Hapa API',
                'version' => 'v1',
                'status' => 'ready',
            ],
        ]);
    }
}
