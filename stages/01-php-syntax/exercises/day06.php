<?php
declare(strict_types=1);
// __DIR__ 是当前 PHP 文件目录，避免从不同工作目录执行时读错文件。
$path = __DIR__ . '/products.json';
try {
    $json = file_get_contents($path);
    if ($json === false) { throw new RuntimeException('Cannot read file'); }
    $products = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    echo 'Products: ' . count($products) . PHP_EOL;
} catch (Throwable $error) {
    // CLI 错误输出到本次终端，不是 docker compose logs php。
    fwrite(STDERR, $error->getMessage() . PHP_EOL);
    exit(1);
}
