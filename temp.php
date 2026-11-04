<?php

declare(strict_types=1);

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



function formatMoney(int $amountMinor, string $currency = 'CNY'): string {
  return sprintf('%s %.2f', $currency, $amountMinor / 100);
}


$items = [
  ['sku' => 'php-book', 'unit_price_minor' => 9900, 'quantity' => 1],
  ['sku' => 'usb-cable', 'unit_price_minor' => 2500, 'quantity' => 2],
];


$subtotal = calculateSubtotal($items);

echo $subtotal;

$discount = $subtotal >= 1000 ? intdiv($subtotal*10, 100) : 0;

$shipping = $subtotal - $discount >= 29900 ? 0 : 2000;

$total = $subtotal - $discount + $shipping;


echo formatMoney($subtotal) . PHP_EOL;
