<?php
// <?php 开始 PHP 代码；纯 PHP 文件可不写结束标记。每条普通语句用 ; 结束。
// declare 是语言指令：strict_types=1 开启严格标量类型检查，不替普通变量指定永久类型。
declare(strict_types=1);
// $ 标记变量，= 赋值；单引号包住字符串。变量名由我们取，不是 PHP 的关键字。
$name = 'Notebook';
// 9900 是整数，金额单位由本练习约定为“分”；Minor 表示较小货币单位，不是语法。
$priceMinor = 9900;
// true 是布尔值“真”，不加引号；'true' 则是字符串，不是同一种类型。
$enabled = true;
// echo 输出文本但不会自动换行；. 把几段内容拼接起来，不是 JS 的 +。
// PHP_EOL 是 PHP 内置的字符串常量；EOL = End Of Line（行结束），作用是换行。
// 这里 PHP 在 Linux 容器里运行，所以 PHP_EOL 是 "\n"，不由外面的 Windows 终端决定。
echo $name . ': ' . $priceMinor . PHP_EOL;
// var_dump 输出类型和值；bool = Boolean（布尔类型），所以这里显示 bool(true)。
var_dump($enabled);
