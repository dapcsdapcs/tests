<?php
require __DIR__ . '/config.php';

$username = 'admin';
$email = 'nicomyw@protonmail.com'; // dummy email
$password = 'nicat7721asdasd';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE password = ?');
    $stmt->execute([$username, $email, $hash, $hash]);
    echo "Admin user created or updated.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>