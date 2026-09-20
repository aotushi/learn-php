<?php
declare(strict_types=1);

// 01.3：本文件只定义函数。require 它不会计算或输出，调用方决定何时调用。
// int $subtotalMinor 是输入类型；: int 是返回类型。金额单位约定为分。
function shipping(int $subtotalMinor): int
{
    if ($subtotalMinor < 0) {
        // throw 交出失败，不会像 return 一样交回普通结果。
        throw new InvalidArgumentException('Subtotal must be non-negative');
    }
    return $subtotalMinor >= 29900 ? 0 : 2000;
}
