<?php

declare(strict_types=1);

namespace LearnPhp\Tests;

use LearnPhp\Pricing\LineItem;
use LearnPhp\Pricing\OrderTotalCalculator;
use LearnPhp\Pricing\PercentageDiscount;
use PHPUnit\Framework\TestCase;

final class OrderTotalCalculatorTest extends TestCase
{
    public function testItCalculatesMultipleItemsAndDiscount(): void
    {
        $items = [
            new LineItem('PHP-BOOK', 9900, 1),
            new LineItem('USB-CABLE', 2500, 2),
        ];

        $result = (new OrderTotalCalculator())->calculate(
            $items,
            new PercentageDiscount(10),
        );

        self::assertSame(14900, $result->subtotalMinor);
        self::assertSame(1490, $result->discountMinor);
        self::assertSame(13410, $result->totalMinor);
    }

    public function testItReturnsZeroForAnEmptyOrder(): void
    {
        $result = (new OrderTotalCalculator())->calculate(
            [],
            new PercentageDiscount(10),
        );

        self::assertSame(0, $result->totalMinor);
    }
}
