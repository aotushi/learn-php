<?php
declare(strict_types=1);

// 本机学习初始化：只建缺少的表、补缺少的种子，不删除或覆盖已有记录。
$pdo = require __DIR__ . '/database.php';
$pdo->exec(file_get_contents(__DIR__ . '/../docker/mysql/init.sql'));
$pdo->exec('CREATE TABLE IF NOT EXISTS learning_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(16) NOT NULL
) ENGINE=InnoDB');
$pdo->exec('CREATE TABLE IF NOT EXISTS learning_tokens (
    token_hash CHAR(64) PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    expires_at BIGINT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES learning_users(id) ON DELETE CASCADE
) ENGINE=InnoDB');
foreach (['admin' => 'admin', 'viewer' => 'viewer'] as $username => $role) {
    $exists = $pdo->prepare('SELECT id FROM learning_users WHERE username = ?');
    $exists->execute([$username]);
    if (!$exists->fetch()) {
        $insert = $pdo->prepare('INSERT INTO learning_users (username, password_hash, role) VALUES (?, ?, ?)');
        $insert->execute([$username, password_hash('LearnPhp123!', PASSWORD_DEFAULT), $role]);
    }
}
echo "Learning tables ready; existing records and passwords preserved.\n";
