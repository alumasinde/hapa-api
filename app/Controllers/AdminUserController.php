<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Database\Connection;
use App\Repository\AuditLogRepository;
use App\Support\Date;
use App\Support\Request;
use App\Support\Response;

final class AdminUserController
{
    public function __construct(private readonly AuditLogRepository $audit = new AuditLogRepository())
    {
    }

    public function index(): never
    {
        $limit = min(100, max(1, (int) (Request::query('limit') ?? 50)));
        $rows = Connection::get()->query('SELECT id, first_name, last_name, display_name, phone, email, status, last_login_at, created_at FROM users WHERE deleted_at IS NULL ORDER BY id DESC LIMIT ' . $limit)->fetchAll();
        Response::json(['users' => $rows]);
    }

    public function show(string $id): never
    {
        $statement = Connection::get()->prepare('SELECT id, first_name, last_name, display_name, phone, email, status, phone_verified_at, email_verified_at, last_login_at, created_at, updated_at FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1');
        $statement->execute(['id' => (int) $id]);
        $user = $statement->fetch();
        if (!$user) Response::error('NOT_FOUND', 'User not found', 404);
        Response::json($user);
    }

    public function status(string $id): never
    {
        $data = Request::json();
        $status = (string) ($data['status'] ?? '');
        if (!in_array($status, ['active', 'suspended', 'disabled'], true)) Response::error('VALIDATION_ERROR', 'Status is invalid', 422, ['status' => 'Use active, suspended or disabled']);
        $statement = Connection::get()->prepare('UPDATE users SET status = :status, updated_at = :updated_at WHERE id = :id AND deleted_at IS NULL');
        $statement->execute(['id' => (int) $id, 'status' => $status, 'updated_at' => Date::now()->format('Y-m-d H:i:s')]);
        if ($statement->rowCount() === 0) Response::error('NOT_FOUND', 'User not found', 404);
        $adminId = RequestContext::adminId();
        $this->audit->log('admin', $adminId, 'user.status_changed', 'user', (int) $id, ['status' => $status]);
        Response::json(['id' => (int) $id, 'status' => $status]);
    }
}
