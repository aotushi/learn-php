<?php
declare(strict_types=1);
// PHP 变量带 $；和 JS 一样，变量保存值。这里的金额单位是“分”。
$name = 'Notebook';
$priceMinor = 9900;
$enabled = true;
echo $name . ': ' . $priceMinor . PHP_EOL; // . 拼接字符串，不是 JS 的 +
var_dump($enabled); // 输出类型和值，类似 console.log，但显示信息不同。
