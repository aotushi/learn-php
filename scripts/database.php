<?php
declare(strict_types=1);

// 容器之间用服务名 mysql，不是宿主机的 localhost。
return new PDO(
    sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        getenv('DB_HOST') ?: 'mysql', getenv('DB_PORT') ?: '3306',
        getenv('DB_DATABASE') ?: 'learning'),
    getenv('DB_USERNAME') ?: 'learnphp',
    getenv('DB_PASSWORD') ?: 'learnphp-local-only',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES => false],
);
