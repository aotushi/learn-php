<?php
// 学习单元 04.3：从项目根打开 docs/README.md#indexes-transactions。
declare(strict_types=1);
// 共用连接文件返回 PDO；require / dirname 的拆解见 public/products.php 与 PHP 阅读速查。
$pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
// EXPLAIN 查看查询计划；* 表示全部列，? 是值占位符，不是执行耗时测量。
$statement = $pdo->prepare('EXPLAIN SELECT * FROM products WHERE sku = ?');
$statement->execute(['LEARN-PHP-001']);
// fetchAll 取计划结果的全部行；print_r 以便于人阅读的结构输出数组，不生成 JSON。
print_r($statement->fetchAll());
// 数据量小不代表索引毫无价值；关注 key、rows 和查询条件的关系。
