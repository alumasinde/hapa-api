<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repository\AdminRepository;
use App\Security\AdminJwtService;
use App\Security\PasswordHasher;
use App\Support\Request;
use App\Support\Response;

final class AdminAuthController
{
    public function __construct(private readonly AdminRepository $admins = new AdminRepository(), private readonly PasswordHasher $hasher = new PasswordHasher(), private readonly AdminJwtService $jwt = new AdminJwtService(), private readonly AdminSessionRepository $sessions = new AdminSessionRepository())
    {
    }

    public function login(): never
    {
        $data = Request::json();
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $password = (string) ($data['password'] ?? '');
        $admin = $email !== '' ? $this->admins->findByEmail($email) : null;

        if (!$admin || !$this->hasher->verify($password, $admin['password_hash']) || $admin['status'] !== 'active') {
            Response::error('UNAUTHORIZED', 'Invalid admin credentials', 401);
        }

        $this->admins->updateLastLogin((int) $admin['id']);
        $sessionId = $this->sessions->create((int) $admin['id']);
        Response::json(['token' => $this->jwt->issue((int) $admin['id'], $sessionId), 'session_id' => $sessionId, 'admin' => ['id' => (int) $admin['id'], 'first_name' => $admin['first_name'], 'last_name' => $admin['last_name'], 'email' => $admin['email'], 'roles' => $this->admins->roles((int) $admin['id']), 'permissions' => $this->admins->permissions((int) $admin['id'])]]);
    }

    public function logout(): never
    {
        $adminId = RequestContext::adminId();
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!$adminId || !preg_match('/^Bearer\\s+(.+)$/i', $token, $matches)) Response::error('UNAUTHORIZED', 'Admin authentication token is required', 401);
        $claims = $this->jwt->claims($matches[1]);
        $this->sessions->revoke($claims['session_id'], $adminId);
        Response::json([], 204);
    }
}
