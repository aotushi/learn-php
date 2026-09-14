<?php
declare(strict_types=1);
$prices = [9900, 2500, 2500];
$total = 0;
foreach ($prices as $price) { // 类似 JS for (const price of prices)
    $total += $price;
}
$shipping = $total >= 29900 ? 0 : 2000;
echo "Subtotal: $total; shipping: $shipping" . PHP_EOL;
var_dump('0' === 0, '0' == 0); // 先预测严格比较和宽松比较的区别。
