<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class UserRepository extends BaseRepository
{
  public function getAllUsers(): array
  {
    return $this->fetchAll(SqlQueries::get("user.all"));
  }

  public function findByUsername(string $username): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("user.by_username"),
      [
        ":username" => $username,
      ]
    );
  }

  public function createUser(string $username, string $role = "Staff"): array
  {
    $this->execute(
      SqlQueries::get("user.insert"),
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
      SqlQueries::get("user.update_role"),
      [
        ":role" => $role,
        ":id" => $userId,
      ]
    );
  }
}
