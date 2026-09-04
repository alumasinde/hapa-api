<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Security\PasswordHasher;
use App\Support\Request;
use App\Support\Response;
use App\Support\Validator;

final class ProfileController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly SessionRepository $sessions = new SessionRepository(),
        private readonly PasswordHasher $hasher = new PasswordHasher(),
    ) {
    }

    public function me(): never
    {
        Response::json($this->publicUser($this->user()));
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

        if (!empty($data['email']) && $this->users->emailExists((string) $data['email'], (int) $user['id'])) {
            Response::error('VALIDATION_ERROR', 'Email address is already registered', 422, ['email' => 'This email address is already registered']);
        }

        Response::json($this->publicUser($this->users->updateProfile((int) $user['id'], $data)));
    }

    public function setPin(): never
    {
        $user = $this->user();
        $data = Request::json();
        $pin = (string) ($data['pin'] ?? '');

        if (!preg_match('/^[0-9]{4,8}$/', $pin)) {
            Response::error('VALIDATION_ERROR', 'PIN must contain 4 to 8 digits', 422, ['pin' => 'PIN must contain 4 to 8 digits']);
        }

        if (!empty($user['pin_hash'])) {
            $currentPin = (string) ($data['current_pin'] ?? '');

            if (!$this->hasher->verify($currentPin, $user['pin_hash'])) {
                Response::error('UNAUTHORIZED', 'Current PIN is incorrect', 401);
            }
        }

        $this->users->updatePin((int) $user['id'], $this->hasher->hash($pin));
        Response::json(['message' => 'PIN updated']);
    }

    public function changePassword(): never
    {
        $user = $this->user();
        $data = Request::json();
        $validator = (new Validator($data))
            ->required('current_password')
            ->required('new_password')
            ->min('new_password', 8);

        if ($validator->fails()) {
            Response::error('VALIDATION_ERROR', 'Password details are invalid', 422, $validator->errors());
        }

        if (!$this->hasher->verify((string) $data['current_password'], $user['password_hash'])) {
            Response::error('UNAUTHORIZED', 'Current password is incorrect', 401);
        }

        $this->users->updatePassword((int) $user['id'], $this->hasher->hash((string) $data['new_password']));
        $this->sessions->revokeAllForUser((int) $user['id']);
        Response::json(['message' => 'Password updated. Please sign in again.']);
    }

    public function logoutAll(): never
    {
        $user = $this->user();
        $this->sessions->revokeAllForUser((int) $user['id']);
        Response::json([], 204);
    }

    private function user(): array
    {
        $userId = RequestContext::userId();

        if (!$userId) {
            Response::error('UNAUTHORIZED', 'Authentication token is required', 401);
        }

        $user = $this->users->find($userId);

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
