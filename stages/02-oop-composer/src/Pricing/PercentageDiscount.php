<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

use InvalidArgumentException;

final readonly class PercentageDiscount implements DiscountPolicy
{
    public function __construct(private int $percent)
    {
        if ($this->percent < 0 || $this->percent > 100) {
            throw new InvalidArgumentException('折扣百分比必须在 0 到 100 之间。');
        }
    }

    public function discountFor(int $subtotalMinor): int
    {
        return intdiv($subtotalMinor * $this->percent, 100);
    }
}
