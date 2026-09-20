<?php
// 学习单元 01.4：从项目根打开 docs/README.md#unit-01-4。
declare(strict_types=1);
// 01.4：外层是列表，内层是按键取值的商品数组。
$products = [
    ['sku' => 'CABLE', 'price_minor' => 2500],
    ['sku' => 'BOOK', 'price_minor' => 9900],
];
// array_filter 接收数组和判断函数，保留判断为 true 的项以及原来的键。
// fn 的表达式自动返回；这里 => 连接参数与结果，不是上面的数组键值。
$expensive = array_filter($products, fn (array $p): bool => $p['price_minor'] >= 5000);
// BOOK 的原键为 1；编码时不连续的数字键会成为 JSON 对象，先观察。
echo 'Filtered: ' . json_encode($expensive, JSON_THROW_ON_ERROR) . PHP_EOL;
// array_values 只接收数组，没有回调；将键重新排成 0、1……。
$reindexed = array_values($expensive);
echo 'List: ' . json_encode($reindexed, JSON_THROW_ON_ERROR) . PHP_EOL;
// array_map 接收变换函数和数组；每项商品变成一个 SKU 字符串。
$skus = array_map(fn (array $p): string => $p['sku'], $reindexed);
echo 'SKUs: ' . json_encode($skus, JSON_THROW_ON_ERROR) . PHP_EOL;
// JSON_THROW_ON_ERROR 让编码失败抛异常，避免把失败误认为空结果。
