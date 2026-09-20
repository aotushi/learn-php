<?php
// 学习单元 04.4：从项目根打开 docs/README.md#unit-04-4。
declare(strict_types=1);
$pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
// 只操作本次事务新增的临时商品，不触碰已有库存。
// random_bytes(6) 产生 6 个随机字节；bin2hex 转成 12 个十六进制字符，减少 SKU 冲突。
$sku = 'TX-' . bin2hex(random_bytes(6));
try {
    // beginTransaction 开始事务；后续操作使用同一连接，成功时本应 commit 提交，此例故意不走成功路径。
    $pdo->beginTransaction();
    // INSERT INTO ... VALUES 新增行；execute 数组里的三个值按 ? 的顺序传入。
    $insert = $pdo->prepare('INSERT INTO products (sku, name, stock_qty) VALUES (?, ?, ?)');
    $insert->execute([$sku, 'Transaction exercise', 5]);
    // UPDATE ... SET 改值；WHERE 同时限定自己的 SKU 和库存条件，AND 要求两者都成立。
    $change = $pdo->prepare('UPDATE products SET stock_qty = stock_qty - 2 WHERE sku = ? AND stock_qty >= 2');
    $change->execute([$sku]);
    // rowCount 返回本次更新影响的行数；不是商品库存值。
    echo 'Updated rows: ' . $change->rowCount() . PHP_EOL;
    throw new RuntimeException('Simulated failure');
} catch (Throwable $e) {
    // inTransaction 检查事务是否仍在进行；rollBack 撤销本事务，单独 throw 不等于已回滚。
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    echo 'Rolled back: ' . $e->getMessage() . PHP_EOL;
}
// COUNT(*) 统计匹配行数；fetchColumn 取第一行的第一列（计数），不返回整张表。
$check = $pdo->prepare('SELECT COUNT(*) FROM products WHERE sku = ?');
$check->execute([$sku]);
echo 'Remaining test rows: ' . $check->fetchColumn() . PHP_EOL;
