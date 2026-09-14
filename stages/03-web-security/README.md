# 阶段 03：HTTP 与输入输出安全

主线见 [第 8、12 天](../../docs/lessons/02-foundations.md)。public/index.php 演示输出转义；public/products.php 演示参数验证、PDO 和 JSON。

```powershell
# 启动原生 PHP 开发网页服务
docker compose --profile web up -d web
```

预期：http://localhost:8080 可提交名字；数据库初始化后 /products.php 返回商品。练习：输入 HTML 观察文字转义，传负数 minimum 观察 422。

本目录仅限本机只读教学，不提供生产认证。受保护写接口从 ThinkPHP 学起。Cookie/Session、CSRF、防上传攻击是后续专题；先理解浏览器会自动附带 Cookie，因此 Cookie 认证的写请求需要 CSRF 防护。当前 API 用显式 Authorization Bearer 头，不使用自动附带的登录 Cookie；这不消除 XSS 或公开部署的其他风险。
