<?php
declare(strict_types=1);
// 告诉客户端正文是 JSON；header 只设置响应头，不会自动把数组编码。
header('Content-Type: application/json; charset=utf-8');
// 04.2：只读查询演示。所有写操作留给受保护的 ThinkPHP。
try {
    // dirname(__DIR__, 3) 从本文件目录向上三级到仓库根；require 执行文件，并接收它 return 的 PDO 对象。
    $pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
    // 验证非负整数：有效返回整数，无参数返回 null，无效返回 false；内层 options 数组设置最小值。
    $minimum = filter_input(INPUT_GET, 'minimum', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
    if ($minimum === false) {
        // http_response_code 设置 HTTP 状态；echo 写 JSON 正文，exit 阻止继续执行数据库查询。
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'minimum 必须是非负整数。']);
        exit;
    }
    // ? 是 SQL 值占位符，不是 PHP 三元运算；prepare 准备语句，execute 用数组按顺序提供值。
    $statement = $pdo->prepare('SELECT id, sku, name, price_minor FROM products WHERE price_minor >= ? ORDER BY id');
    // ?? 只在左边不存在或为 null 时用 0；合法的整数 0 不会被当成缺少。
    $statement->execute([$minimum ?? 0]);
    // fetchAll 取出所有结果行；JSON_UNESCAPED_UNICODE 保留可读的 Unicode 字符，而非 \u 转义形式。
    echo json_encode(['success' => true, 'data' => $statement->fetchAll()], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    // 详细错误写服务器日志；客户端仅收到通用错误。$e 是我们取的变量名，不是特殊语法。
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database unavailable']);
}
