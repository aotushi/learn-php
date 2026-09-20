<?php
declare(strict_types=1);

// 容器之间用服务名 mysql，不是宿主机的 localhost。
// 此文件 return 一个 PDO 连接，让 require 它的代码可以接收；不是向终端输出。
// sprintf 返回连接描述；getenv 读环境变量，?: 在值为假时使用右侧默认值。
// PDO:: 是类常量访问：异常模式、按列名取行、关闭模拟预处理。详解见阶段 04 的 01-pdo-products.php。
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
