<?php

declare(strict_types=1);

// getenv 读取容器环境变量；?: 在左侧为 false、空串等“假值”时使用右侧默认值，不同于只判断 null 的 ??。
$host = getenv('DB_HOST') ?: 'mysql';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'learning';
$username = getenv('DB_USERNAME') ?: 'learnphp';
$password = getenv('DB_PASSWORD') ?: 'learnphp-local-only';

// new PDO 建立数据库连接；参数依次是连接描述(DSN)、用户名、密码、选项数组。
// 双引号中的 {$host} 等会插入变量值；utf8mb4 是数据库连接字符集。
$pdo = new PDO(
    "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
    $username,
    $password,
    [
        // :: 访问 PDO 的类常量；ERRMODE_EXCEPTION 让数据库错误抛异常，不静默忽略。
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // FETCH_ASSOC 返回按列名取值的关联数组，如 $product['sku']。
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // false 关闭模拟预处理，交由数据库处理预处理语句。
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
);

$minimumPrice = 5000;
// SELECT 选列，FROM 指定表，WHERE 限制行，ORDER BY ... ASC 按价格升序。
// prepare 准备 SQL；:minimum_price 是命名占位符，不能把用户输入拼进 SQL 结构。
$statement = $pdo->prepare(
    'SELECT sku, name, price_minor
     FROM products
     WHERE price_minor >= :minimum_price
     ORDER BY price_minor ASC',
);
// execute 将占位符绑定到 5000 再执行；这里绑定的是值，不是列名/表名。
$statement->execute(['minimum_price' => $minimumPrice]);

foreach ($statement as $product) {
    // printf 按顺序填参数并直接输出；%s 是字符串，%.2f 是两位小数，%d 是整数，\n 是换行。
    printf(
        "%s | %s | %.2f\n",
        $product['sku'],
        $product['name'],
        $product['price_minor'] / 100,
    );
}
