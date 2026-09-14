# 阶段 08：小型商品管理 API

跟随 [接口手动练习](../../docs/api-workshop.md) 完成登录、创建、查询、修改、删除；跟随 [第 19–24 天](../../docs/lessons/04-integration.md) 在 Vue 页面操作。

参考实现位于阶段 07 的 app/app/controller、service、middleware。基本范围：商品、验证、只读/编辑权限、过期与退出。商品网址是一对多关系练习；订单、分类、复杂库存和分页作为后续扩展。

```powershell
# 运行真实 API 验证；检查自身测试记录在结束时清理
docker compose exec php php scripts/api-smoke.php
```

预期 API checks passed。必做：手动重复 SKU 得到 409、非法价格得到 422、viewer 写入得到 403、过期令牌得到 401。

验收：页面数据在 MySQL 持久保存，错误可读，服务器实际执行权限检查。已有大型 Vue 后台需适配认证、守卫和商品契约，不再声称“只改地址即可”。
