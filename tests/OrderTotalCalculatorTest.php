<?php

declare(strict_types=1);

namespace LearnPhp\Tests;

use LearnPhp\Pricing\LineItem;
use LearnPhp\Pricing\OrderTotalCalculator;
use LearnPhp\Pricing\PercentageDiscount;
use PHPUnit\Framework\TestCase;

// extends 继承 PHPUnit 提供的 TestCase；public 且以 test 开头的方法会被此测试框架发现。
final class OrderTotalCalculatorTest extends TestCase
{
    // : void 表示不返回业务结果；测试通过断言或异常来报告结果。
    public function testItCalculatesMultipleItemsAndDiscount(): void
    {
        $items = [
            new LineItem('PHP-BOOK', 9900, 1),
            new LineItem('USB-CABLE', 2500, 2),
        ];

        // 括号包住 new 创建的对象，随后 ->calculate 调用它的方法；不是调用类名本身。
        $result = (new OrderTotalCalculator())->calculate(
            $items,
            new PercentageDiscount(10),
        );

        // self:: 指当前类可用的静态方法；assertSame(预期, 实际) 同时检查类型和值。
        // 02.4 不计运费，因此总额 13410 与 01.6 含运费示例的 15410 不矛盾。
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
        // ::class 得到完整类名字符串，不会 new 一个对象；开头的 \ 从全局命名空间开始找。
        // 先声明预期异常，再触发操作；没抛出、或抛错类型，测试都不算通过。
        $this->expectException(\InvalidArgumentException::class);
        new LineItem('TEST', -1, 1);
    }

    public function testZeroQuantityIsRejected(): void
    {
        // 独立练习时先遮住本参考断言；能重跑这个现成测试不等于自己会设计边界。
        $this->expectException(\InvalidArgumentException::class);
        new LineItem('TEST', 100, 0);
    }
}
