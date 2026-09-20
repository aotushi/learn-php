# 对象与 Composer 源码索引

学习说明、命令、练习和完成记录都在 [唯一手册](../../docs/README.md)。本页只把文件对应到同一个学习单元；点击单元号进入具体步骤。

| 学习单元 | 文件 | 本次用途 |
| --- | --- | --- |
| [02.1](../../docs/README.md#unit-02-1) | [examples/01-price-object.php](examples/01-price-object.php) | 展开的单文件对象 |
| [02.2](../../docs/README.md#unit-02-2) | [src/Pricing/LineItem.php](src/Pricing/LineItem.php) | 先读这个类，比较属性提升和 readonly |
| [02.2](../../docs/README.md#unit-02-2) | [examples/02-require-line-item.php](examples/02-require-line-item.php) | 再读入口，明确 require 与 use |
| [02.3](../../docs/README.md#unit-02-3) | [../../composer.json](../../composer.json) | 查看 LearnPhp 的类名到路径映射 |
| [02.3](../../docs/README.md#unit-02-3) | [examples/03-autoload-line-item.php](examples/03-autoload-line-item.php) | 入口加载一次 Composer，再创建对象 |
| [02.4 选读](../../docs/README.md#unit-02-4) | [examples/04-order-total.php](examples/04-order-total.php) | 先运行组合入口，观察三项金额 |
| [02.4 选读](../../docs/README.md#unit-02-4) | [src/Pricing/DiscountPolicy.php](src/Pricing/DiscountPolicy.php) | LineItem 之后读：约定的方法 |
| [02.4 选读](../../docs/README.md#unit-02-4) | [src/Pricing/PercentageDiscount.php](src/Pricing/PercentageDiscount.php) | 然后看实现约定的优惠类 |
| [02.4 选读](../../docs/README.md#unit-02-4) | [src/Pricing/OrderTotal.php](src/Pricing/OrderTotal.php) | 保存结果的对象 |
| [02.4 选读](../../docs/README.md#unit-02-4) | [src/Pricing/OrderTotalCalculator.php](src/Pricing/OrderTotalCalculator.php) | 最后看组合，再回到入口 |

参考入口在 examples/；已有 exercises/ 和旧路径下的实验均保留。自己的 my-* 文件按手册创建或继续编辑，不覆盖已有作业。src/public/app 中的文件按职责命名，通过表中入口使用。
