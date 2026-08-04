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
        $subtotal = array_sum(
            array_map(
                static fn (LineItem $item): int => $item->subtotalMinor(),
                $items,
            ),
        );
        $discount = min($subtotal, $discountPolicy->discountFor($subtotal));

        return new OrderTotal(
            subtotalMinor: $subtotal,
            discountMinor: $discount,
            totalMinor: $subtotal - $discount,
        );
    }
}
