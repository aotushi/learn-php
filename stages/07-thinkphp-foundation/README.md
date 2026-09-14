# 阶段 07：ThinkPHP 8.1 基础

按 [第 13–18 天](../../docs/lessons/03-thinkphp.md) 学习，比旧路线更早进入框架。app 来自官方 8.1.3 模板，composer.lock 固定核心 8.1.4。

```powershell
# 安装锁定依赖、准备学习表并等待 API 健康
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1
# 列出 URL 与处理函数的映射
docker compose exec thinkphp php think route:list
# 请求无需登录的健康接口，内部也会查询数据库
Invoke-RestMethod http://localhost:18081/api/health
```

预期 success True。阅读顺序：app/public/index.php → app/route/app.php → app/app/controller/Product.php → app/app/service/ProductInput.php → app/config/database.php。

简单登录已作为可用的教学支架提供，第 21 天再解释实现。商品 API 未带令牌返回 401 是正确行为，跟随 [手动练习](../../docs/api-workshop.md) 获取本机令牌。强制注册路由、统一 JSON 错误；没有任意跨域开放。

练习：健康响应添加一个固定字段；说明路由、控制器、验证、数据库各负责什么。失败查看 thinkphp 服务日志和 app/runtime/log，不反复重装。
