<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Security\RateLimiter;
use App\Services\AuthService;
use App\Support\Request;
use App\Support\Response;
use App\Support\Validator;

final class AuthController
{
    public function __construct(
        private readonly AuthService $auth = new AuthService(),
        private readonly RateLimiter $limits = new RateLimiter(),
    ) {
    }

    public function register(): never
    {
        $data = Request::json();
        $validator = (new Validator($data))
            ->required('first_name')->required('last_name')->required('display_name')
            ->required('password')->min('password', 8)
            ->phone('phone')->email('email');

        if ($validator->fails()) {
            Response::error('VALIDATION_ERROR', 'Registration details are invalid', 422, $validator->errors());
        }

        if (empty($data['phone']) && empty($data['email'])) {
            Response::error('VALIDATION_ERROR', 'Phone or email is required', 422, ['phone' => 'Phone or email is required']);
        }

        if (!$this->limits->allow('register', Request::ip(), 10, 3600)) {
            Response::error('RATE_LIMITED', 'Too many registration attempts', 429);
        }

        Response::json($this->auth->register($data), 201);
    }

    public function login(): never
    {
        $data = Request::json();
        $validator = (new Validator($data))->required('login')->required('password');

        if ($validator->fails()) {
            Response::error('VALIDATION_ERROR', 'Login details are invalid', 422, $validator->errors());
        }

        if (!$this->limits->allow('login', Request::ip(), 20, 900)) {
            Response::error('RATE_LIMITED', 'Too many login attempts', 429);
        }

        $result = $this->auth->login((string) $data['login'], (string) $data['password'], $data['device_id'] ?? null, $data['platform'] ?? null);

        if (!$result) {
            Response::error('UNAUTHORIZED', 'Invalid login credentials', 401);
        }

        Response::json($result);
    }

    public function refresh(): never
    {
        $data = Request::json();

        if (empty($data['refresh_token'])) {
            Response::error('VALIDATION_ERROR', 'Refresh token is required', 422, ['refresh_token' => 'This field is required']);
        }

        $result = $this->auth->refresh((string) $data['refresh_token']);

        if (!$result) {
            Response::error('UNAUTHORIZED', 'Invalid refresh token', 401);
        }

        Response::json($result);
    }

    public function logout(): never
    {
        $data = Request::json();

        if (!empty($data['refresh_token'])) {
            $this->auth->logout((string) $data['refresh_token']);
        }

        Response::json([], 204);
    }
}
