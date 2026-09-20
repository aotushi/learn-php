<?php
// 学习单元 02.1：从项目根打开 docs/README.md#unit-02-1。
declare(strict_types=1);

// 02.1：先用展开写法看清属性、构造函数和方法，不先压缩为属性提升。
class ProductPrice
{
    public int $minor; // public 允许类外读写；int 是属性类型。

    public function __construct(int $minor)
    {
        if ($minor < 0) {
            throw new InvalidArgumentException('Price must be non-negative');
        }
        $this->minor = $minor; // $this 是本次创建的对象，-> 访问属性。
    }

    public function label(): string
    {
        // number_format 返回显示字符串；/100 只用于展示元，计算仍用整数分。
        return 'CNY ' . number_format($this->minor / 100, 2);
    }
}

$price = new ProductPrice(9900); // new 创建对象并调用 __construct。
echo $price->label() . PHP_EOL;
// 公开可写属性仍能在构造后被改动；02.2 的 LineItem 再介绍 readonly。
