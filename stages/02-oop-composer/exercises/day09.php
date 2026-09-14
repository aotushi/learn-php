<?php
declare(strict_types=1);
// readonly 禁止初始化后改属性。-> 访问对象，类似 JS 的 .。
final readonly class ProductPrice
{
    public function __construct(public int $minor)
    {
        if ($minor < 0) { throw new InvalidArgumentException('Price must not be negative'); }
    }
    public function label(): string { return 'CNY ' . number_format($this->minor / 100, 2); }
}
$price = new ProductPrice(9900);
echo $price->label() . PHP_EOL;
