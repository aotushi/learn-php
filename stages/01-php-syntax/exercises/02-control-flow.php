<?php
declare(strict_types=1);
// [] 创建列表，逗号分隔元素；这里存三个整数价格（分）。基础符号见 docs/README.md#php-reference。
$prices = [9900, 2500, 2500];
// 累加器先置 0，否则无法表达“还没计算任何商品”。
$total = 0;
// foreach ... as 每次把一个元素交给 $price；{} 是每次循环执行的代码块，类似 JS for…of。
foreach ($prices as $price) {
    // += 是累加赋值：等价于 $total = $total + $price。
    $total += $price;
}
// >= 表示大于或等于；条件 ? 真时的值 : 假时的值。达到门槛运费为 0，否则 2000。
$shipping = $total >= 29900 ? 0 : 2000;
// 双引号中的 $total/$shipping 会替换为变量值；PHP_EOL 的换行解释见 01-variables.php。
echo "Subtotal: $total; shipping: $shipping" . PHP_EOL;
// === 同时比较类型和值；== 可能先转换类型。此处依次 false、true，不能推广成 JS 的全部规则。
var_dump('0' === 0, '0' == 0);
