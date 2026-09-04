<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Security\PasswordHasher;

final class PasswordService
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly SessionRepository $sessions = new SessionRepository(),
        private readonly PasswordHasher $hasher = new PasswordHasher(),
    ) {
    }

    public function change(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->users->find($userId);

        if (!$user || !$this->hasher->verify($currentPassword, $user['password_hash'])) {
            return false;
        }

        $this->users->updatePassword($userId, $this->hasher->hash($newPassword));
        $this->sessions->revokeAllForUser($userId);

        return true;
    }
}
