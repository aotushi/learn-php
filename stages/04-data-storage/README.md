# 阶段 04：MariaDB 与 Valkey

## 启动

```powershell
# 启用 data 配置组，在后台启动 MariaDB、Valkey 等数据服务
docker compose --profile data up -d

# 查看 data 配置组中的容器状态；ps = process status
docker compose --profile data ps

# 在 php 服务中执行 PDO 数据库查询示例；PDO = PHP Data Objects
docker compose exec php php stages/04-data-storage/exercises/pdo_products.php
```

`--profile data` 表示选择数据服务组，`-d` 是 `--detach`（后台运行），`exec` 来自 `execute`（执行）。完整说明见 [命令与缩写词典](../../docs/command-glossary.md)。

首次创建数据库时会自动生成 `learning.products` 表和两条示例数据。

## 学习内容

- 数据建模、约束、连接、聚合、事务和隔离。
- 参数绑定、迁移、批量写入与错误处理。
- `EXPLAIN`、复合索引、选择性和慢查询。
- Valkey/Redis 的数据结构、过期、缓存失效和会话。

## 必做练习

1. 设计 `customers`、`orders`、`order_items`，保证订单保留成交时价格。
2. 用一个事务创建订单并扣减库存，模拟中途失败并确认回滚。
3. 分别为 SKU 查询、名称搜索、价格区间写 `EXPLAIN`。
4. 将商品详情缓存 60 秒；更新数据库后主动删除缓存。
5. 解释为何不能把 Valkey 当作唯一的订单存储。

## 验收

能根据具体查询证明索引是否有效；能说明事务边界和缓存一致性策略。
