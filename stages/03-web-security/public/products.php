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
    // 占位符是 SQL 中“留给实际值的位置”：先在 prepare(...) 的 SQL 字符串中写好，
    // 再由 execute(...) 接收数组，提供这些位置对应的实际值，并执行查询。
    // 所以不是在 execute 中写两种占位符；execute 中写的是值，命名写法还要用数组键说明对应关系。
    // SQL 结构和值分开提供，不要把用户输入直接拼进 SQL；占位符代表数据值，不代表表名或列名。
    //
    // 1. 位置占位符：这里的 ? 写在 SQL 字符串里，不是 PHP 的三元运算符。
    // SQL 从左到右的第 1 个 ? 对应 execute 数组的第 1 个值，第 2 个 ? 对应第 2 个值。
    $statement = $pdo->prepare('SELECT id, sku, name, price_minor FROM products WHERE price_minor >= ? ORDER BY id');
    // $minimum ?? 0 是 PHP 表达式：$minimum 为 null（没有 minimum 参数）时用 0，否则用原值。
    // [...] 把算出的值放进数组；例如 $minimum 为 5000，就相当于 execute([5000])。
    // 数组的第 1 个值 5000 交给 SQL 的第 1 个 ?，查询价格不低于 5000 分的商品。
    // 注意：SQL 中的 ? 留出位置；PHP 中的 ?? 选择默认值，它们是两种不同的语法。
    $statement->execute([$minimum ?? 0]);
    // 多个位置占位符举例：WHERE price_minor >= ? AND stock_qty >= ?
    // 配套 execute([5000, 2]) 表示最低价 5000 分、最低库存 2；交换数组中的值会改变条件含义。
    //
    // 2. 命名占位符：04.1 使用的 :minimum_price 给这个位置起了名字。
    // 本例也可以把上面的 prepare 和 execute 一起替换成以下两行（这里只作说明，不重复执行）：
    // $statement = $pdo->prepare('SELECT id, sku, name, price_minor FROM products WHERE price_minor >= :minimum_price ORDER BY id');
    // $statement->execute(['minimum_price' => $minimum ?? 0]);
    // 对应关系：SQL 的 :minimum_price ← 数组键 'minimum_price' ← 右边表达式算出的实际值。
    // :minimum_price 是 SQL 占位符，'minimum_price' 是 PHP 数组键，$minimum 才是 PHP 变量。
    // 数组键必须与占位符名称对应（这里的键省略冒号）；PHP 变量名不必与占位符同名。
    // 多个命名占位符举例：WHERE price_minor >= :minimum_price AND stock_qty >= :minimum_stock
    // 配套 execute(['minimum_stock' => 2, 'minimum_price' => 5000])，按名称对应，数组键的排列顺序不影响结果。
    // 两种写法完成同样的事：位置写法按顺序对应，命名写法按名称对应；同一条 SQL 不要混用 ? 和 :名称。
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
