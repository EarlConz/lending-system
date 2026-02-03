<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class UserRepository extends BaseRepository
{
  public function getAllUsers(): array
  {
    return $this->fetchAll("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
  }

  public function findByUsername(string $username): ?array
  {
    return $this->fetchOne("SELECT id, username, role FROM users WHERE username = :username LIMIT 1", [
      ":username" => $username,
    ]);
  }

  public function createUser(string $username, string $role = "Staff"): array
  {
    $this->execute(
      "INSERT INTO users (username, role) VALUES (:username, :role)",
      [
        ":username" => $username,
        ":role" => $role,
      ]
    );

    $id = (int) $this->db()->lastInsertId();
    return [
      "id" => $id,
      "username" => $username,
      "role" => $role,
    ];
  }

  public function updateRole(int $userId, string $role): bool
  {
    return $this->execute(
      "UPDATE users SET role = :role WHERE id = :id",
      [
        ":role" => $role,
        ":id" => $userId,
      ]
    );
  }
}
