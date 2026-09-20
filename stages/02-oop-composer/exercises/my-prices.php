<?php
declare(strict_types=1);

use LearnPhp\Pricing\LineItem;





require dirname(__DIR__, 3) . '/vendor/autoload.php';
/**
 * Fatal error: Uncaught Error: Class "LearnPhp\Pricing\LineItem" not found in /workspace/stages/02-oop-composer/exercises/my-prices.php:14
 */



$item = new LineItem('BOOK', 2500, 2);
$item2 = new LineItem('BOOK', 9000, 1);


echo $item->subtotalMinor() . PHP_EOL;
echo $item2->subtotalMinor() .PHP_EOL;