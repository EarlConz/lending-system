<?php
declare(strict_types=1);

return [
  "host" => getenv("DB_HOST") ?: "localhost",
  "port" => getenv("DB_PORT") ?: "3306",
  "dbname" => getenv("DB_NAME") ?: "lending_systeme",
  "user" => getenv("DB_USER") ?: "root",
  "pass" => getenv("DB_PASS") ?: "",
  "charset" => "utf8mb4",
];
