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
    // 04.1 的 :minimum_price 是“命名占位符”；这里的 ? 是“位置占位符”，也不是 PHP 三元运算。
    // 两种写法都会把 SQL 结构和值分开。区别仅是：命名占位符按名称绑定，? 按从左到右的位置绑定。
    $statement = $pdo->prepare('SELECT id, sku, name, price_minor FROM products WHERE price_minor >= ? ORDER BY id');
    // ?? 先得到实际值：minimum 缺少/null 时用 0；合法整数 0 不会被当成缺少。
    // 外层 [...] 创建一个只有一项的绑定列表；第 1 项绑定 SQL 中第 1 个 ?。
    // 等价的命名写法是 WHERE price_minor >= :minimum_price，随后：
    // execute(['minimum_price' => $minimum ?? 0])。
    $statement->execute([$minimum ?? 0]);
    // fetchAll 取出所有结果行；JSON_UNESCAPED_UNICODE 保留可读的 Unicode 字符，而非 \u 转义形式。
    echo json_encode(['success' => true, 'data' => $statement->fetchAll()], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    // Throwable 也可能包含数据库以外的错误；具体原因只写服务器日志，不向客户端泄露内部信息。
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Internal server error',
    ], JSON_UNESCAPED_UNICODE);
}
