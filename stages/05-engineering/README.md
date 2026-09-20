# 测试与排错源码索引

学习说明、命令、练习和完成记录都在 [唯一手册](../../docs/README.md)。本页只把文件对应到同一个学习单元；点击单元号进入具体步骤。

| 学习单元 | 文件 | 本次用途 |
| --- | --- | --- |
| [05.1](../../docs/README.md#unit-05-1) | [../../tests/ShippingTest.php](../../tests/ShippingTest.php) | 从已学函数认识断言，运行 test:basics |
| [05.1](../../docs/README.md#unit-05-1) | [../../phpunit.xml](../../phpunit.xml) | 根 tests 的发现与自动加载入口 |
| [05.2](../../docs/README.md#unit-05-2) | [../../tests](../../tests) | 自己的 MyPriceInputTest.php 放这里 |
| [09.1](../../docs/README.md#unit-09-1) | [../../scripts/lint.php](../../scripts/lint.php) | 后期检查 PHP 语法 |
| [09.1](../../docs/README.md#unit-09-1) | [../../scripts/api-smoke.php](../../scripts/api-smoke.php) | 主线 API 回归；扩展需显式选项 |

参考入口在 examples/；已有 exercises/ 和旧路径下的实验均保留。自己的 my-* 文件按手册创建或继续编辑，不覆盖已有作业。src/public/app 中的文件按职责命名，通过表中入口使用。

本目录对应教学主题；测试统一放根 tests，不在这里另建一套发现规则。
