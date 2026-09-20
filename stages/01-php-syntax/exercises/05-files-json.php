<?php
declare(strict_types=1);
// __DIR__ 是当前 PHP 文件目录，避免从不同工作目录执行时读错文件。
$path = __DIR__ . '/products.json';
try {
    // file_get_contents 接收路径，成功返回整份文件的字符串；失败返回 false，通常还会有警告。
    // $json = file_get_contents($path);
    $json = false;
    echo($json);
    // === false 只判断读取失败，不把空字符串误判为失败；RuntimeException 表示运行时异常。
    if ($json === false) { throw new RuntimeException('Cannot read file'); }
    // 四个参数：JSON 文本、对象转关联数组(true)、最大嵌套深度(512)、失败时抛异常的选项。
    // decode 解码：文本 → PHP 值；与 04-arrays-json.php 的 encode（编码）方向相反。
    $products = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    // count 返回数组元素数量，不是 JSON 文本的字符数。
    echo 'Products: ' . count($products) . PHP_EOL;
} catch (Throwable $error) {
    // Throwable 是可抛出错误/异常共同实现的接口；这里接住 JSON 异常或上面的读取失败异常。

    var_dump(get_debug_type($error)); //string(16) "RuntimeException"
    echo(get_debug_type(($error))) . PHP_EOL;  //RuntimeException

    // fwrite 把第二个参数写到第一个参数指定的流；STDERR = standard error（标准错误输出）。
    // CLI 错误显示在本次终端，不是 docker compose logs php。
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    // exit(1) 结束整个脚本，非零退出码表示失败；不等于 HTTP 状态码 1。
    exit(1);
}
