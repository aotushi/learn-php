<?php
// 学习单元 01.3：从项目根打开 docs/README.md#unit-01-3。
declare(strict_types=1);
// 01.3：require_once 执行文件一次，让其中定义的 shipping 可供调用。
// __DIR__ 是当前文件的目录；. 拼接路径。02.2 会专门练习多文件加载。
require_once __DIR__ . '/support/shipping.php';

// return 交回结果；echo 才把结果打印出来。先预测门槛两侧。
echo shipping(29899) . PHP_EOL;
echo shipping(29900) . PHP_EOL;
try {
    shipping(-1);
} catch (InvalidArgumentException $error) {
    // -> 调用异常对象的方法；这里只处理预期的输入错误。
    echo $error->getMessage() . PHP_EOL;
}
