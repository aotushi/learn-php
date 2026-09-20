<?php
// 学习单元 01.5：从项目根打开 docs/README.md#unit-01-5。
declare(strict_types=1);
// 01.5：顺序是路径 -> 文件文本 -> PHP 值 -> 业务结构。
$path = __DIR__ . '/products.json';
try {
    // 成功返回字符串；失败返回 false 并可能发出警告。空字符串不等于 false。
    $text = file_get_contents($path);
    if ($text === false) {
        throw new RuntimeException('Cannot read file');
    }
    // true: JSON 对象转关联数组；512: 最大嵌套深度；坏 JSON 抛异常。
    $products = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
    // 合法 JSON 也可能是 null 或数字。先确认数组，再确认是 0 起始连续键的列表。
    // 关联数组解码会把 {} 也变成 []；还要用开头的 [ 确认原文确实是 JSON 列表。
    // ltrim 去掉开头空白；str_starts_with 判断是否以指定文字开头。语法正确性已由解码检查。
    if (!is_array($products) || !array_is_list($products) || !str_starts_with(ltrim($text), '[')) {
        throw new UnexpectedValueException('Expected a product list');
    }
    foreach ($products as $product) {
        // 本课只约定每项是数组且 sku 是非空字符串，不声称已验证价格等所有业务字段。
        if (!is_array($product) || !isset($product['sku'])
            || !is_string($product['sku']) || trim($product['sku']) === '') {
            throw new UnexpectedValueException('Each product needs a non-empty sku');
        }
    }
    echo 'Products: ' . count($products) . PHP_EOL;
} catch (Throwable $error) {
    // STDERR 是终端的错误流；非零退出码让调用者知道运行失败。
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    exit(1);
}
