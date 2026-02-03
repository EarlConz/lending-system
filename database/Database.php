<?php
declare(strict_types=1);

class Database
{
  private static ?PDO $pdo = null;

  public static function connection(): PDO
  {
    if (self::$pdo instanceof PDO) {
      return self::$pdo;
    }

    $config = require __DIR__ . "/config.php";
    $host = $config["host"];
    $port = $config["port"];
    $dbname = $config["dbname"];
    $charset = $config["charset"];

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
    self::$pdo = new PDO(
      $dsn,
      $config["user"],
      $config["pass"],
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]
    );

    return self::$pdo;
  }
}
