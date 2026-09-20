<?php

declare(strict_types=1);

// namespace 给类名分组，\ 分隔名称层级，不是文件路径；Composer 负责把类名映射到文件。
namespace LearnPhp\Pricing;

// use 让后面可以使用这个类名；不等于执行/复制另一个 PHP 文件。
use InvalidArgumentException;

final readonly class LineItem
{
    // 02.2：将 02.1 的普通属性与赋值压缩成“属性提升”；参数前的 public 同时声明属性。
    /**
     * // 参数前面加 public / protected / private，就同时完成了：    // ① 声明属性      // ② 在构造函数里赋值
     */
    public function __construct(
        public string $sku,
        public int $unitPriceMinor,
        public int $quantity,
    ) {
        if ($this->sku === '') {
            throw new InvalidArgumentException('SKU 不能为空。');
        }

        if ($this->unitPriceMinor < 0) {
            throw new InvalidArgumentException('商品价格不能为负数。');
        }

        if ($this->quantity < 1) {
            throw new InvalidArgumentException('商品数量必须至少为 1。');
        }
    }

    public function subtotalMinor(): int
    {
        // $this 指当前这件商品；返回整数小计，不包含折扣或运费。
        return $this->unitPriceMinor * $this->quantity;
    }
}
