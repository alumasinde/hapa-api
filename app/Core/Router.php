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
        $offset = 0;
        $pattern = '';

        while (preg_match('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $path, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $full = $match[0][0];
            $position = $match[0][1];
            $name = $match[1][0];
            $pattern .= preg_quote(substr($path, $offset, $position - $offset), '#');
            $pattern .= '(?P<' . $name . '>[^/]+)';
            $parameters[] = $name;
            $offset = $position + strlen($full);
        }

        $pattern .= preg_quote(substr($path, $offset), '#');

        $this->routes[$method][] = [
            'handler' => $handler,
            'authenticated' => $authenticated,
            'parameters' => $parameters,
            'pattern' => '#^' . $pattern . '$#',
        ];

        return $this;
    }
}
