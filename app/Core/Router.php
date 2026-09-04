<?php

declare(strict_types=1);

namespace App\Core;

use App\Middleware\AuthMiddleware;
use App\Middleware\RequestIdMiddleware;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler, bool $authenticated = false): self
    {
        return $this->add('GET', $path, $handler, $authenticated);
    }

    public function post(string $path, callable $handler, bool $authenticated = false): self
    {
        return $this->add('POST', $path, $handler, $authenticated);
    }

    public function patch(string $path, callable $handler, bool $authenticated = false): self
    {
        return $this->add('PATCH', $path, $handler, $authenticated);
    }

    public function dispatch(string $method, string $path): bool
    {
        $route = $this->routes[$method][$path] ?? null;

        if (!$route) {
            return false;
        }

        $run = $route['handler'];

        if ($route['authenticated']) {
            $authenticated = $run;
            $run = static fn (): mixed => (new AuthMiddleware())->handle($authenticated);
        }

        return (new RequestIdMiddleware())->handle($run) === null || true;
    }

    private function add(string $method, string $path, callable $handler, bool $authenticated): self
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'authenticated' => $authenticated,
        ];

        return $this;
    }
}
