# 阶段 04：MySQL 8.4 与 PDO

主线见 [第 11–12 天](../../docs/lessons/02-foundations.md)；事务与索引在 [第 26 天](../../docs/lessons/05-delivery.md) 巩固。本月不要求实现订单/库存系统。

```powershell
# 只启动 PHP、MySQL，等待数据库健康后才执行查询
docker compose --profile data up -d --wait php mysql
# 为新旧本地数据卷补齐表和缺少的学习种子，不重置已有记录
docker compose exec php php scripts/setup-data.php
# 运行绑定参数的只读查询
docker compose exec php php stages/04-data-storage/exercises/pdo_products.php
```

预期看到示例商品；失败先看 mysql 健康状态及连接配置。表结构见 docker/mysql/init.sql。首次启动由 init.sh 选择 MYSQL_DATABASE，已有卷由 setup-data.php 增量补齐；修改 SQL 不会自动更改已有表结构。

必做：解释主键/唯一约束、绑定参数、SKU 索引，运行 transaction.php 看到失败回滚且无残留测试行。商品表的 stock_qty 只是教学字段，不等于实现库存流水和并发扣减。

扩展：分类、订单事务、库存变化历史、分页查询、Valkey 缓存。缓存使用独立 cache profile，data profile 不启动缓存。等核心商品闭环通过后再学。
