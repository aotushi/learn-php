<?php
// 学习单元 02.4：从项目根打开 docs/README.md#unit-02-4。
declare(strict_types=1);
use LearnPhp\Pricing\LineItem;
use LearnPhp\Pricing\OrderTotalCalculator;
use LearnPhp\Pricing\PercentageDiscount;

require dirname(__DIR__, 3) . '/vendor/autoload.php';
// 02.4（选读）：LineItem 管一项，PercentageDiscount 管优惠，计算器组合它们。
$items = [new LineItem('BOOK', 9900, 1), new LineItem('CABLE', 2500, 2)];
$discount = new PercentageDiscount(10);
// calculate 要求 DiscountPolicy；实现了该接口的 PercentageDiscount 能被传入。
$result = (new OrderTotalCalculator())->calculate($items, $discount);
// 返回 OrderTotal 对象；-> 读取其只读结果。此例始终按百分比优惠，不含运费。
echo 'Subtotal: ' . $result->subtotalMinor . PHP_EOL;
echo 'Discount: ' . $result->discountMinor . PHP_EOL;
echo 'Total: ' . $result->totalMinor . PHP_EOL;
