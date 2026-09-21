<?php

declare(strict_types=1);

function readPriceMinor(array $input): int
{
    // 先确认字段存在；array_key_exists 会把“键不存在”和“值为 null”区分开。
    if (!array_key_exists('price_minor', $input)) {
        throw new InvalidArgumentException('缺少 price_minor。');
    }

    $priceMinor = $input['price_minor'];

    // 外层是数组，不代表其中的字段一定是整数；这里不自动转换字符串、布尔值或小数。
    if (!is_int($priceMinor)) {
        throw new InvalidArgumentException('price_minor 必须是整数。');
    }

    // 金额以“分”为单位；0 和上下限都合法，只有范围之外的整数被拒绝。
    if ($priceMinor < 0 || $priceMinor > 100_000_000) {
        throw new InvalidArgumentException('price_minor 必须在 0 到 100000000 之间。');
    }

    return $priceMinor;
}
