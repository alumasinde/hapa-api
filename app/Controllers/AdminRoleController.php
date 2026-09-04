<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\AdminRepository;
use App\Repository\AuditLogRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminRoleController
{
    public function __construct(private readonly AdminRepository $admins = new AdminRepository(), private readonly AuditLogRepository $audit = new AuditLogRepository())
    {
    }

    public function assign(string $adminId): never
    {
        $data = Request::json();
        $roleKey = (string) ($data['role_key'] ?? '');
        if ($roleKey === '' || !$this->admins->assignRole((int) $adminId, $roleKey)) Response::error('VALIDATION_ERROR', 'Role is invalid', 422, ['role_key' => 'Role does not exist']);
        $this->audit->log('admin', RequestContext::adminId(), 'admin.role_assigned', 'admin_user', (int) $adminId, ['role_key' => $roleKey]);
        Response::json(['admin_id' => (int) $adminId, 'roles' => $this->admins->roles((int) $adminId)]);
    }

    public function permissions(string $roleKey): never
    {
        $data = Request::json();
        $keys = $data['permissions'] ?? null;
        if (!is_array($keys) || array_filter($keys, 'is_string') !== $keys || !$this->admins->syncRolePermissions($roleKey, $keys)) Response::error('VALIDATION_ERROR', 'Role or permissions are invalid', 422);
        $this->audit->log('admin', RequestContext::adminId(), 'role.permissions_changed', 'role', null, ['role_key' => $roleKey, 'permissions' => array_values($keys)]);
        Response::json(['role_key' => $roleKey, 'permissions' => array_values($keys)]);
    }
}
