<?php

declare(strict_types=1);

namespace LearnPhp\Pricing;

use InvalidArgumentException;

final readonly class LineItem
{
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
        return $this->unitPriceMinor * $this->quantity;
    }
}
