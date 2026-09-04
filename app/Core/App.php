<?php
declare(strict_types=1);
namespace App\Core;

use App\Support\Env;
use App\Support\Logger;
use App\Support\Response;

final class App
{
    public static function boot(string $basePath): self
    {
        Env::load($basePath . '/.env');
        date_default_timezone_set((string) Env::get('APP_TIMEZONE', 'UTC'));
        return new self($basePath);
    }

    public function __construct(private readonly string $basePath) {}

    public function run(): void
    {
        try {
            $router = new Router();
            $register = require $this->basePath . '/routes/api.php';
            $register($router);
            $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
            $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
            if ($router->dispatch($method, $path)) return;
            Response::error('NOT_FOUND', 'Endpoint not found', 404);
        } catch (\JsonException $e) {
            Logger::error('request.invalid_json', ['message'=>$e->getMessage()]);
            Response::error('VALIDATION_ERROR', 'Request body must contain valid JSON', 422);
        } catch (\Throwable $e) {
            Logger::error('request.unhandled_exception', ['exception'=>get_class($e),'message'=>$e->getMessage()]);
            $debug = filter_var(Env::get('APP_DEBUG', false), FILTER_VALIDATE_BOOL);
            $message = $debug ? $e->getMessage() : 'An unexpected server error occurred';
            Response::error('INTERNAL_ERROR', $message, 500);
        }
    }
}