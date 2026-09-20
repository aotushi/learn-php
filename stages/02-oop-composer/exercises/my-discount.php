<?php
/**
 * 在 exercises/my-discount.php 仿照入口组合对象，改成一件 101 分的商品，优惠依次 0%、10%、100%；总额应为 101、91、0。解释 intdiv 为什么得到 10 分优惠而不是 11。每次仍由入口加载一次 Composer。
 */


declare(strict_types=1);

use LearnPhp\Pricing\LineItem;
use LearnPhp\Pricing\OrderTotalCalculator;
use LearnPhp\Pricing\PercentageDiscount;


require dirname(__DIR__, 3) . '/vendor/autoload.php';


$items = [
  new LineItem('BOOK', 101, 1)
];

// $discount = new PercentageDiscount(0);
$discount = new PercentageDiscount(10);
// $discount = new PercentageDiscount(100);


$result = (new OrderTotalCalculator()) -> calculate($items, $discount);


echo 'Subtotal: ' . $result->subtotalMinor . PHP_EOL;
echo 'Discount: ' . $result->discountMinor . PHP_EOL;
echo 'Total: ' . $result->totalMinor . PHP_EOL;
