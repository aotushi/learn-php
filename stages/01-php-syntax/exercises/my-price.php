<?php

declare(strict_types=1);

function readPriceMinor(array $input): int
{
    // 先确认字段存在；array_key_exists 会把“键不存在”和“值为 null”区分开。
    if (!array_key_exists('price_minor', $input)) {
        throw new InvalidArgumentException('缺少 price_minor。');
    }

    $priceMinor = $input['price_minor'];

    // 先检查原始类型，不用 += 0：它会把 true、null 和带空格的字符串误转成整数。
    if (is_string($priceMinor)) {
        // \A 与 \z 要求检查完整字符串；[0-9]+ 只允许一个或多个 ASCII 数字；preg_match 匹配成功返回 1。
        // 因此前导零合法，空串、空格、正负号和小数点都不合法。
        if (preg_match('/\A[0-9]+\z/', $priceMinor) !== 1) {
            throw new InvalidArgumentException('price_minor 必须是十进制整数字符串。');
        }
        // 内容验证通过后再转换；"0099" 变成整数 99，再做范围检查。
        $priceMinor = (int) $priceMinor;
    } elseif (!is_int($priceMinor)) {
        // 布尔、浮点数、数组和 null 均不能作为价格。
        throw new InvalidArgumentException('price_minor 必须是整数或十进制整数字符串。');
    }

    // 金额以“分”为单位；0 和上下限都合法，只有范围之外的整数被拒绝。
    if ($priceMinor < 0 || $priceMinor > 100_000_000) {
        throw new InvalidArgumentException('price_minor 必须在 0 到 100000000 之间。');
    }

    return $priceMinor;
}
