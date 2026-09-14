<?php
declare(strict_types=1);
$pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
$statement = $pdo->prepare('EXPLAIN SELECT * FROM products WHERE sku = ?');
$statement->execute(['LEARN-PHP-001']);
print_r($statement->fetchAll());
// 数据量小不代表索引毫无价值；关注 key、rows 和查询条件的关系。
