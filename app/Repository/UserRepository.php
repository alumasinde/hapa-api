<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class UserRepository
{
    public function create(array $data, string $passwordHash): array
    {
        $now = Date::now()->format('Y-m-d H:i:s');
        $statement = Connection::get()->prepare('INSERT INTO users (first_name, last_name, display_name, phone, email, password_hash, created_at, updated_at) VALUES (:first_name, :last_name, :display_name, :phone, :email, :password_hash, :created_at, :updated_at)');
        $statement->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'display_name' => $data['display_name'],
            'phone' => $data['phone'] ?: null,
            'email' => $data['email'] ?: null,
            'password_hash' => $passwordHash,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $this->find((int) Connection::get()->lastInsertId());
    }

    public function find(int $id): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function findByLogin(string $login): ?array
    {
        $statement = Connection::get()->prepare('SELECT * FROM users WHERE deleted_at IS NULL AND (email = :login OR phone = :login) LIMIT 1');
        $statement->execute(['login' => $login]);

        return $statement->fetch() ?: null;
    }

    public function updateProfile(int $id, array $data): ?array
    {
        $statement = Connection::get()->prepare('UPDATE users SET first_name = :first_name, last_name = :last_name, display_name = :display_name, email = :email, updated_at = :updated_at WHERE id = :id AND deleted_at IS NULL');
        $statement->execute([
            'id' => $id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'display_name' => $data['display_name'],
            'email' => $data['email'] ?: null,
            'updated_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);

        return $this->find($id);
    }

    public function updatePin(int $id, string $pinHash): void
    {
        Connection::get()->prepare('UPDATE users SET pin_hash = :pin_hash, updated_at = :updated_at WHERE id = :id')->execute([
            'id' => $id,
            'pin_hash' => $pinHash,
            'updated_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
