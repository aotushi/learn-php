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

    public function testFullDiscountAndIntegerRounding(): void
    {
        $items = [new LineItem('TEST', 101, 1)];
        $calculator = new OrderTotalCalculator();
        self::assertSame(0, $calculator->calculate($items, new PercentageDiscount(100))->totalMinor);
        self::assertSame(91, $calculator->calculate($items, new PercentageDiscount(10))->totalMinor);
    }

    public function testInvalidPriceIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new LineItem('TEST', -1, 1);
    }

    public function testZeroQuantityIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new LineItem('TEST', 100, 0);
    }
}
