# 阶段 05：测试与排错

按 [第 24 天](../../docs/lessons/04-integration.md) 和 [第 25–30 天](../../docs/lessons/05-delivery.md) 操作。

```powershell
# 检查独立业务规则
docker compose exec php composer test
# 检查真实 API 和本地数据库操作
docker compose exec php php scripts/api-smoke.php
```

预期全部通过；集成检查只清理自身随机 SKU 的商品和令牌。不对学习者已有数据进行清空。

故障记录只需五行：现象、最短复现、预期/实际、证据/根因、修复后验证。必做：把一个断言临时改错、观察失败、恢复；离线一次浏览器请求再恢复，确认重试成功。Xdebug 单步调试、大函数重构留作选做，不要求操作不存在的“100 行函数”。
