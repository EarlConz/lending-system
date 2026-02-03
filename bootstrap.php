<?php
declare(strict_types=1);

date_default_timezone_set("Asia/Manila");

spl_autoload_register(function (string $class): void {
  $paths = [
    __DIR__ . "/database/{$class}.php",
    __DIR__ . "/dal/{$class}.php",
  ];

  foreach ($paths as $path) {
    if (is_file($path)) {
      require_once $path;
      return;
    }
  }
});
