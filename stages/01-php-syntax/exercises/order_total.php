<?php

declare(strict_types=1);

/**
 * @param list<array{sku: string, unit_price_minor: int, quantity: int}> $items
 */
function calculateSubtotal(array $items): int
{
    $subtotal = 0;

    foreach ($items as $item) {
        if ($item['unit_price_minor'] < 0 || $item['quantity'] < 1) {
            throw new InvalidArgumentException('价格不能为负数，数量必须至少为 1。');
        }

        $subtotal += $item['unit_price_minor'] * $item['quantity'];
    }

    return $subtotal;
}

function formatMoney(int $amountMinor, string $currency = 'CNY'): string
{
    return sprintf('%s %.2f', $currency, $amountMinor / 100);
}

$items = [
    ['sku' => 'PHP-BOOK', 'unit_price_minor' => 9900, 'quantity' => 1],
    ['sku' => 'USB-CABLE', 'unit_price_minor' => 2500, 'quantity' => 2],
];

$subtotal = calculateSubtotal($items);
$discount = $subtotal >= 10000 ? intdiv($subtotal * 10, 100) : 0;
$shipping = $subtotal - $discount >= 29900 ? 0 : 2000;
$total = $subtotal - $discount + $shipping;

echo '商品小计：' . formatMoney($subtotal) . PHP_EOL;
echo '优惠金额：' . formatMoney($discount) . PHP_EOL;
echo '运费：' . formatMoney($shipping) . PHP_EOL;
echo '应付总额：' . formatMoney($total) . PHP_EOL;
