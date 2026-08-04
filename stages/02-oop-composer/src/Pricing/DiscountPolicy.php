<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

interface DiscountPolicy
{
    public function discountFor(int $subtotalMinor): int;
}
