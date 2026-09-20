<?php
// 学习单元 01.6：从项目根打开 docs/README.md#unit-01-6。

declare(strict_types=1);

/**
 * PHPDoc 注释给人和工具看；list<array{...}> 描述商品列表结构，不会自动验证字段。
 * @param list<array{sku: string, unit_price_minor: int, quantity: int}> $items
 */
function calculateSubtotal(array $items): int
{
    $subtotal = 0;

    foreach ($items as $item) {
        // || 是逻辑“或”：价格为负或数量小于 1，任一成立就拒绝。
        if ($item['unit_price_minor'] < 0 || $item['quantity'] < 1) {
            throw new InvalidArgumentException('价格不能为负数，数量必须至少为 1。');
        }

        // * 先算单价乘数量，+= 再累加；每次循环仍以整数“分”计算。
        $subtotal += $item['unit_price_minor'] * $item['quantity'];
    }

    return $subtotal;
}

// string 声明字符串；= 'CNY' 是省略第二个参数时的默认值，不是把每次输入改成 CNY。
function formatMoney(int $amountMinor, string $currency = 'CNY'): string
{
    // sprintf 返回格式化字符串，不直接输出；%s 放字符串，%.2f 显示两位小数，参数按顺序填入。
    // / 100 只用于展示“元”；核心金额计算仍用整数，避免浮点误差累积。
    return sprintf('%s %.2f', $currency, $amountMinor / 100);
}

$items = [
    ['sku' => 'PHP-BOOK', 'unit_price_minor' => 9900, 'quantity' => 1],
    ['sku' => 'USB-CABLE', 'unit_price_minor' => 2500, 'quantity' => 2],
];

$subtotal = calculateSubtotal($items);
// intdiv 是整数除法，丢弃小数部分；这里算 10% 优惠，未到 10000 分则优惠为 0。
$discount = $subtotal >= 10000 ? intdiv($subtotal * 10, 100) : 0;
// 注意使用折后金额判断免运费；这是本练习的业务规则，不是 PHP 的规定。
$shipping = $subtotal - $discount >= 29900 ? 0 : 2000;
$total = $subtotal - $discount + $shipping;

echo '商品小计：' . formatMoney($subtotal) . PHP_EOL;  //14900->149.00
echo '优惠金额：' . formatMoney($discount) . PHP_EOL;  //1490->14.90
echo '运费：' . formatMoney($shipping) . PHP_EOL;     //2000->20.00
echo '应付总额：' . formatMoney($total) . PHP_EOL;     //15410->154.10
