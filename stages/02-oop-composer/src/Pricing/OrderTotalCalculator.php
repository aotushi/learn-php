<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

final class OrderTotalCalculator
{
    /**
     * @param list<LineItem> $items
     */
    public function calculate(array $items, DiscountPolicy $discountPolicy): OrderTotal
    {
        // 从内向外：array_map 把每件商品变成小计列表，array_sum 再求和；不同于只筛选的 array_filter。
        // static fn 表示这个箭头函数不绑定 $this；这里只需要显式传入的 $item。
        $subtotal = array_sum(
            array_map(
                static fn (LineItem $item): int => $item->subtotalMinor(),
                $items,
            ),
        );
        // min 取较小值，防止优惠超过小计；discountFor 是接口约定的方法，不是 PHP 内置函数。
        $discount = min($subtotal, $discountPolicy->discountFor($subtotal));

        // subtotalMinor: 是“命名参数”：按构造函数参数名传值，不是数组的 key => value。
        // 返回的是 OrderTotal 对象，不是 JSON；02.4 不含运费，区别于 01.6 的完整金额示例。
        return new OrderTotal(
            subtotalMinor: $subtotal,
            discountMinor: $discount,
            totalMinor: $subtotal - $discount,
        );
    }
}
