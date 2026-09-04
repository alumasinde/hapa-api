<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function patch(string $path, callable $handler): self
    {
        return $this->add('PATCH', $path, $handler);
    }

    public function dispatch(string $method, string $path): bool
    {
        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            return false;
        }

        $handler();

        return true;
    }

    private function add(string $method, string $path, callable $handler): self
    {
        $this->routes[$method][$path] = $handler;

        return $this;
    }
}
