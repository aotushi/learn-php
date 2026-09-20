<?php
// 学习单元 02.3：从项目根打开 docs/README.md#unit-02-3。
declare(strict_types=1);
use LearnPhp\Pricing\LineItem;

// 02.3：从 examples 向上三级到项目根；加载 Composer 生成的入口，注册类的自动加载器。
// 帮你自动加载类文件，不用再手动写 require / require_once。
require dirname(__DIR__, 3) . '/vendor/autoload.php';
// composer.json 将 LearnPhp\ 映射到 src/；余下 Pricing\LineItem 对应 Pricing/LineItem.php。
// new 首次需要这个类时，加载器执行定义文件。类文件无需各自重复 require。
$item = new LineItem('BOOK', 2500, 2);
echo $item->subtotalMinor() . PHP_EOL;
