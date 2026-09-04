<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Database\Connection;
use App\Security\PasswordHasher;
use App\Support\Date;
use App\Support\Env;

Env::load(dirname(__DIR__) . '/.env');

[$script, $email, $password, $firstName, $lastName] = array_pad($argv, 5, null);

if (!$email || !$password || !$firstName || !$lastName) {
    fwrite(STDERR, "Usage: php bin/create-admin.php email password first_name last_name\n");
    exit(1);
}

$pdo = Connection::get();
$now = Date::now()->format('Y-m-d H:i:s');
$statement = $pdo->prepare('SELECT id FROM admin_users WHERE email = :email LIMIT 1');
$statement->execute(['email' => strtolower($email)]);
$adminId = $statement->fetchColumn();

if (!$adminId) {
    $pdo->prepare('INSERT INTO admin_users (first_name, last_name, email, password_hash, status, created_at, updated_at) VALUES (:first_name, :last_name, :email, :password_hash, \'active\', :created_at, :updated_at)')->execute([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => strtolower($email),
        'password_hash' => (new PasswordHasher())->hash($password),
        'created_at' => $now,
        'updated_at' => $now,
    ]);
    $adminId = (int) $pdo->lastInsertId();
} else {
    $pdo->prepare('UPDATE admin_users SET first_name = :first_name, last_name = :last_name, password_hash = :password_hash, status = \'active\', updated_at = :updated_at WHERE id = :id')->execute([
        'id' => $adminId,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'password_hash' => (new PasswordHasher())->hash($password),
        'updated_at' => $now,
    ]);
}

$role = $pdo->query("SELECT id FROM roles WHERE role_key = 'super_admin' LIMIT 1")->fetchColumn();
if (!$role) {
    fwrite(STDERR, "super_admin role is missing. Run php bin/migrate.php admin first.\n");
    exit(1);
}

$pdo->prepare('INSERT IGNORE INTO admin_user_roles (admin_user_id, role_id) VALUES (:admin_id, :role_id)')->execute(['admin_id' => $adminId, 'role_id' => $role]);
echo "Admin ready: " . strtolower($email) . PHP_EOL;
