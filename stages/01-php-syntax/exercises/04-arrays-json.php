<?php
declare(strict_types=1);
// PHP 的 array 既能表示列表，也能表示键值映射；不像 JS 分 Array 与普通 Object。
// 外层 [] 是商品列表；内层 'sku' => 'BOOK' 把字符串键关联到值，=> 此时不是函数箭头。
$products = [
    ['sku' => 'BOOK', 'price_minor' => 9900],
    ['sku' => 'CABLE', 'price_minor' => 2500],
];
// array_filter(列表, 判断函数) 逐项保留判断为 true 的元素，不修改原列表，并保留原来的键。
// fn 是箭头函数语法：array $p 接收一项数组，: bool 声明返回布尔值，=> 后的表达式自动返回。
// $p['price_minor'] 读取字段；此处的 => 与上面的“键 => 值”处于不同语法位置。
$expensive = array_filter($products, fn (array $p): bool => $p['price_minor'] >= 5000);
// 从内向外读：array_values 重编号为 0、1… → json_encode 转成 JSON 文本 → echo 输出。
// JSON_THROW_ON_ERROR 是选项常量，让编码失败抛异常，不要把失败当成正常的空输出。
echo json_encode(array_values($expensive), JSON_THROW_ON_ERROR) . PHP_EOL;
// array_filter 保留原键；array_values 重编号，保证编码后仍是 JSON 列表。
