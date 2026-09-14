<?php
declare(strict_types=1);
// PHP 的 array 既能表示列表，也能表示键值映射；不像 JS 分 Array 与普通 Object。
$products = [
    ['sku' => 'BOOK', 'price_minor' => 9900],
    ['sku' => 'CABLE', 'price_minor' => 2500],
];
$expensive = array_filter($products, fn (array $p): bool => $p['price_minor'] >= 5000);
echo json_encode(array_values($expensive), JSON_THROW_ON_ERROR) . PHP_EOL;
// array_filter 保留原键；array_values 重编号，保证编码后仍是 JSON 列表。
