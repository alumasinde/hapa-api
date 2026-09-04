<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\UserRepository;
use App\Security\JwtService;
use App\Security\PasswordHasher;
use App\Support\Request;
use App\Support\Response;
use App\Support\Validator;

final class ProfileController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly JwtService $jwt = new JwtService(),
        private readonly PasswordHasher $hasher = new PasswordHasher(),
    ) {
    }

    public function me(): never
    {
        $user = $this->user();

        Response::json($this->publicUser($user));
    }

    public function update(): never
    {
        $user = $this->user();
        $data = array_merge($user, Request::json());
        $validator = (new Validator($data))
            ->required('first_name')->required('last_name')->required('display_name')
            ->email('email');

        if ($validator->fails()) {
            Response::error('VALIDATION_ERROR', 'Profile details are invalid', 422, $validator->errors());
        }

        Response::json($this->publicUser($this->users->updateProfile((int) $user['id'], $data)));
    }

    public function setPin(): never
    {
        $user = $this->user();
        $data = Request::json();
        $validator = (new Validator($data))->required('pin')->min('pin', 4);

        if ($validator->fails() || !preg_match('/^[0-9]{4,8}$/', (string) ($data['pin'] ?? ''))) {
            Response::error('VALIDATION_ERROR', 'PIN must contain 4 to 8 digits', 422, ['pin' => 'PIN must contain 4 to 8 digits']);
        }

        $this->users->updatePin((int) $user['id'], $this->hasher->hash((string) $data['pin']));

        Response::json(['message' => 'PIN updated']);
    }

    private function user(): array
    {
        $header = Request::header('Authorization');

        if (!$header || !preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        try {
            $id = $this->jwt->userId($matches[1]);
        } catch (\Throwable) {
            Response::error('UNAUTHORIZED', 'Authentication token is invalid', 401);
        }

        $user = $this->users->find($id);

        if (!$user || $user['status'] !== 'active') {
            Response::error('UNAUTHORIZED', 'User is unavailable', 401);
        }

        return $user;
    }

    private function publicUser(array $user): array
    {
        return [
            'id' => (int) $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'display_name' => $user['display_name'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'created_at' => $user['created_at'],
        ];
    }
}
