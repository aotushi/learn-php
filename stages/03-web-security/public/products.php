<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
// Day 12：只读查询演示，不是生产管理接口。所有写操作留给受保护的 ThinkPHP。
try {
    $pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
    $minimum = filter_input(INPUT_GET, 'minimum', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($minimum === false) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'minimum 必须是非负整数。']);
        exit;
    }
    $statement = $pdo->prepare('SELECT id, sku, name, price_minor FROM products WHERE price_minor >= ? ORDER BY id');
    $statement->execute([$minimum ?? 0]);
    echo json_encode(['success' => true, 'data' => $statement->fetchAll()], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database unavailable']);
}
