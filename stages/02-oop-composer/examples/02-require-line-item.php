<?php
// 学习单元 02.2：从项目根打开 docs/README.md#unit-02-2。
declare(strict_types=1);
// 02.2：use 为完整类名引入短名称，本身不会执行 LineItem.php。
use LearnPhp\Pricing\LineItem;

/**
 * // 使用简称
* $item = new LineItem('BOOK', 2500, 2);

* // 完整名称仍然可以使用
* $other = new \LearnPhp\Pricing\LineItem('BOOK', 2500, 2);
 */

// 先执行定义文件，PHP 才认识里面的类；路径相对于本文件，而不是终端目录。
// require_once __DIR__ . '/../src/Pricing/LineItem.php';
require __DIR__ . '/../src/Pricing/LineItem.php';
/**
 * require_once 表达式和 require 表达式完全相同，唯一区别是 PHP 会检查该文件是否已经被包含过，如果是则不会再次包含。
 */
$item = new LineItem('BOOK', 2500, 2);
echo $item->subtotalMinor() . PHP_EOL; // 5000 分；没有优惠或运费。
