<?php

declare(strict_types=1);

// 声明这个文件里定义的类，属于 LearnPhp\Pricing 这个命名空间。所以这个类的完整类名是：LearnPhp\Pricing\PercentageDiscount
// PercentageDiscount.php 是定义类的地方，它必须自己声明自己属于哪个命名空间。
namespace LearnPhp\Pricing;

use InvalidArgumentException;

// implements 表示兑现接口约定；与 extends 继承父类不同。
final readonly class PercentageDiscount implements DiscountPolicy
{
    // private 只允许本类内部直接访问百分比，外部通过 discountFor 方法使用它。
    public function __construct(private int $percent)
    {
        if ($this->percent < 0 || $this->percent > 100) {
            throw new InvalidArgumentException('折扣百分比必须在 0 到 100 之间。');
        }
    }

    public function discountFor(int $subtotalMinor): int
    {
        // 用整数除法计算折扣分数；如 101 分的 10% 得 10 分，不自动四舍五入为 11。
        return intdiv($subtotalMinor * $this->percent, 100);
    }
}
