<?php

declare(strict_types=1);

namespace App\Core;

use App\Middleware\AuthMiddleware;
use App\Middleware\IdempotencyMiddleware;
use App\Middleware\RequestIdMiddleware;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler, bool $authenticated = false): self
    {
        return $this->add('GET', $path, $handler, $authenticated);
    }

    public function post(string $path, callable $handler, bool $authenticated = false, bool $idempotent = false): self
    {
        return $this->add('POST', $path, $handler, $authenticated, $idempotent);
    }

    public function patch(string $path, callable $handler, bool $authenticated = false, bool $idempotent = false): self
    {
        return $this->add('PATCH', $path, $handler, $authenticated, $idempotent);
    }

    public function getAdmin(string $path, callable $handler, string $permission): self
    {
        return $this->add('GET', $path, $handler, false, false, $permission);
    }

    public function postAdmin(string $path, callable $handler, string $permission): self
    {
        return $this->add('POST', $path, $handler, false, false, $permission);
    }

    public function patchAdmin(string $path, callable $handler, string $permission): self
    {
        return $this->add('PATCH', $path, $handler, false, false, $permission);
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

            if ($route['idempotent']) {
                $handler = $run;
                $routeKey = $method . ':' . $route['path'];
                $run = static fn (): mixed => (new IdempotencyMiddleware())->handle($routeKey, $handler);
            }

            if ($route['admin_permission'] !== null) {
                $handler = $run;
                $permission = $route['admin_permission'];
                $run = static fn (): mixed => (new AdminAuthMiddleware())->handle(static fn (): mixed => (new PermissionMiddleware())->handle($permission, $handler));
            }

            if ($route['authenticated']) {
                $handler = $run;
                $run = static fn (): mixed => (new AuthMiddleware())->handle($handler);
            }

            (new RequestIdMiddleware())->handle($run);

            return true;
        }

        return false;
    }

    private function add(string $method, string $path, callable $handler, bool $authenticated, bool $idempotent = false, ?string $adminPermission = null): self
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
            'idempotent' => $idempotent,
            'parameters' => $parameters,
            'path' => $path,
            'admin_permission' => $adminPermission,
            'pattern' => '#^' . $pattern . '$#',
        ];

        return $this;
    }
}
