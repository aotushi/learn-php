<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../stages/01-php-syntax/examples/support/shipping.php';

// 05.1：当前单元用 --filter ShippingTest，只检查已经学过的运费函数。
final class ShippingTest extends TestCase
{
    public function testShippingBoundary(): void
    {
        // assertSame(预期, 实际)
        self::assertSame(2000, shipping(29899));
        self::assertSame(0, shipping(29900));
    }

    public function testNegativeSubtotalIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        shipping(-1);
    }
}
