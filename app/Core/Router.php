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
        foreach ($this->routes[$method] ?? [] as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $arguments = [];
            foreach ($route['parameters'] as $parameter) {
                $arguments[] = $matches[$parameter] ?? null;
            }

            $run = static fn (): mixed => ($route['handler'])(...$arguments);

            if ($route['authenticated']) {
                $handler = $run;
                $run = static fn (): mixed => (new AuthMiddleware())->handle($handler);
            }

            (new RequestIdMiddleware())->handle($run);

            return true;
        }

        return false;
    }

    private function add(string $method, string $path, callable $handler, bool $authenticated): self
    {
        $parameters = [];
        $pattern = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', static function (array $matches) use (&$parameters): string {
            $parameters[] = $matches[1];

            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $path);

        $this->routes[$method][] = [
            'handler' => $handler,
            'authenticated' => $authenticated,
            'parameters' => $parameters,
            'pattern' => '#^' . $pattern . '$#',
        ];

        return $this;
    }
}
