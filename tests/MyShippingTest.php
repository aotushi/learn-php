<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../stages/01-php-syntax/examples/support/shipping.php';


final class MyShippingTest extends TestCase
{
    public function testMyShippingBoundary(): void
    {
        // 曾把预期写成 1000 并观察失败；恢复后确认未达门槛时运费为 2000 分。
        self::assertSame(2000, shipping(1000));
    }
}
