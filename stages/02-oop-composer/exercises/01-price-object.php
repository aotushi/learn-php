<?php
declare(strict_types=1);
// class 定义类；final 禁止继承；readonly 让这里的已初始化属性不能再次赋值，不必先深究全部细节。
final readonly class ProductPrice
{
    // __construct 是 new 时调用的构造函数；public 允许类外访问。
    // public int $minor 同时接收构造参数、声明并赋值一个整数属性，叫“构造器属性提升”。
    public function __construct(public int $minor)
    {
        if ($minor < 0) { throw new InvalidArgumentException('Price must not be negative'); }
    }
    // $this 是当前对象；->minor 读属性。number_format(金额, 2) 返回保留两位小数的字符串。
    public function label(): string { return 'CNY ' . number_format($this->minor / 100, 2); }
}
// new 创建对象；9900 传入构造函数。->label() 调用方法，括号不可省略。
$price = new ProductPrice(9900);
echo $price->label() . PHP_EOL;
