<?php
declare(strict_types=1);
// int 是真正的运行时参数约束；不同于编译后被擦除的 TypeScript 类型。
function shipping(int $subtotalMinor): int
{
    if ($subtotalMinor < 0) {
        throw new InvalidArgumentException('subtotal must not be negative');
    }
    return $subtotalMinor >= 29900 ? 0 : 2000;
}
echo shipping(29899) . PHP_EOL;
echo shipping(29900) . PHP_EOL;
try { shipping(-1); } catch (InvalidArgumentException $error) {
    echo $error->getMessage() . PHP_EOL;
}
