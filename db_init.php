<?php
// Run this script once to create the `users` table.
// Usage: from project root run `php db_init.php` (ensure DATABASE_URL env var set if needed)

/** This script will create the users table if it does not exist. **/
require __DIR__ . '/config.php';
$pdo = require __DIR__ . '/config.php';

$sql = "
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  address VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $pdo->exec($sql);
    echo "users tablosu oluşturuldu veya zaten mevcut.\n";
} catch (Exception $e) {
    echo "Tablo oluşturulurken hata: " . $e->getMessage() . "\n";
}
