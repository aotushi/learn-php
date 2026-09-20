<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

final readonly class OrderTotal
{
    // 保存计算结果的对象；参数即公开只读属性。调用者可用 ->totalMinor 读取，但不能重新赋值。
    public function __construct(
        public int $subtotalMinor,
        public int $discountMinor,
        public int $totalMinor,
    ) {
    }
}
