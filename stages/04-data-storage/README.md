# 数据库源码索引

学习说明、命令、练习和完成记录都在 [唯一手册](../../docs/README.md)。本页只把文件对应到同一个学习单元；点击单元号进入具体步骤。

| 学习单元 | 文件 | 本次用途 |
| --- | --- | --- |
| [04.1](../../docs/README.md#unit-04-1) | [../../docker/mysql/init.sql](../../docker/mysql/init.sql) | 先看 products 字段与约束 |
| [04.1](../../docs/README.md#unit-04-1) | [examples/01-pdo-products.php](examples/01-pdo-products.php) | PDO 连接、参数绑定与查询 |
| [04.2](../../docs/README.md#unit-04-2) | [../../scripts/database.php](../../scripts/database.php) | 返回 PDO 的共用文件 |
| [04.2](../../docs/README.md#unit-04-2) | [../03-web-security/public/products.php](../03-web-security/public/products.php) | 把查询放进原生 HTTP 入口 |
| [04.3，在09.2学习](../../docs/README.md#indexes-transactions) | [examples/02-query-plan.php](examples/02-query-plan.php) | SKU 查询的执行计划 |
| [04.4，在09.2学习](../../docs/README.md#unit-04-4) | [examples/03-transaction.php](examples/03-transaction.php) | 主动失败与临时行回滚 |

参考入口在 examples/；已有 exercises/ 和旧路径下的实验均保留。自己的 my-* 文件按手册创建或继续编辑，不覆盖已有作业。src/public/app 中的文件按职责命名，通过表中入口使用。
