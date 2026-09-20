<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

// interface 定义“必须提供什么方法”，不提供本例的计算实现；读懂测试即可，策略设计属于扩展。
interface DiscountPolicy
{
    // 只声明签名，所以用 ; 结束而不是方法体；实现类必须提供兼容的参数和返回类型。
    public function discountFor(int $subtotalMinor): int;
}
