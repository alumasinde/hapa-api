<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class AdminRepository
{
    public function findByEmail(string $email): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM admin_users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);

        return $statement->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM admin_users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function permissions(int $adminId): array
    {
        $statement = Connection::get()->prepare('SELECT DISTINCT p.permission_key FROM permissions p JOIN role_permissions rp ON rp.permission_id = p.id JOIN admin_user_roles aur ON aur.role_id = rp.role_id WHERE aur.admin_user_id = :id');
        $statement->execute(['id' => $adminId]);

        return array_column($statement->fetchAll(), 'permission_key');
    }

    public function roles(int $adminId): array
    {
        $statement = Connection::get()->prepare('SELECT r.role_key, r.name FROM roles r JOIN admin_user_roles aur ON aur.role_id = r.id WHERE aur.admin_user_id = :id');
        $statement->execute(['id' => $adminId]);

        return $statement->fetchAll();
    }

 public function updateLastLogin(int $id): void
{
    Connection::get()->prepare(
        'UPDATE admin_users SET last_login_at = NOW(), updated_at = NOW() WHERE id = :id'
    )->execute(['id' => $id]);
}

    public function assignRole(int $adminId, string $roleKey): bool
    {
        $statement = Connection::get()->prepare('SELECT id FROM roles WHERE role_key = :key LIMIT 1');
        $statement->execute(['key' => $roleKey]);
        $roleId = $statement->fetchColumn();

        if (!$roleId) {
            return false;
        }

        Connection::get()->prepare('INSERT IGNORE INTO admin_user_roles (admin_user_id, role_id) VALUES (:admin_id, :role_id)')->execute(['admin_id' => $adminId, 'role_id' => $roleId]);

        return true;
    }

    public function syncRolePermissions(string $roleKey, array $permissionKeys): bool
    {
        $pdo = Connection::get();
        $statement = $pdo->prepare('SELECT id FROM roles WHERE role_key = :key LIMIT 1');
        $statement->execute(['key' => $roleKey]);
        $roleId = $statement->fetchColumn();

        if (!$roleId) {
            return false;
        }

        $pdo->beginTransaction();
        try {
            $pdo->prepare('DELETE FROM role_permissions WHERE role_id = :id')->execute(['id' => $roleId]);
            $permission = $pdo->prepare('SELECT id FROM permissions WHERE permission_key = :key LIMIT 1');
            $insert = $pdo->prepare('INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)');
            foreach (array_unique($permissionKeys) as $key) {
                $permission->execute(['key' => $key]);
                $permissionId = $permission->fetchColumn();
                if (!$permissionId) {
                    $pdo->rollBack();
                    return false;
                }
                $insert->execute(['role_id' => $roleId, 'permission_id' => $permissionId]);
            }
            $pdo->commit();
            return true;
        } catch (\Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }
}
