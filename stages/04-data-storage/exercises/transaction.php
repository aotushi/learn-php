<?php
declare(strict_types=1);
$pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
// 只操作本次事务新增的临时商品，不触碰已有库存。
$sku = 'TX-' . bin2hex(random_bytes(6));
try {
    $pdo->beginTransaction();
    $insert = $pdo->prepare('INSERT INTO products (sku, name, stock_qty) VALUES (?, ?, ?)');
    $insert->execute([$sku, 'Transaction exercise', 5]);
    $change = $pdo->prepare('UPDATE products SET stock_qty = stock_qty - 2 WHERE sku = ? AND stock_qty >= 2');
    $change->execute([$sku]);
    echo 'Updated rows: ' . $change->rowCount() . PHP_EOL;
    throw new RuntimeException('Simulated failure');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    echo 'Rolled back: ' . $e->getMessage() . PHP_EOL;
}
$check = $pdo->prepare('SELECT COUNT(*) FROM products WHERE sku = ?');
$check->execute([$sku]);
echo 'Remaining test rows: ' . $check->fetchColumn() . PHP_EOL;
