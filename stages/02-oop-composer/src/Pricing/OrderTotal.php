<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

final readonly class OrderTotal
{
    public function __construct(
        public int $subtotalMinor,
        public int $discountMinor,
        public int $totalMinor,
    ) {
    }
}
