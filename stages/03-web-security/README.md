# 阶段 03：Web、HTTP 与安全

## 学习内容

- 请求方法、URL、状态码、Header、Cookie 与 Session。
- 输入验证、输出转义、错误响应和日志。
- SQL 注入、XSS、CSRF、文件上传和密码安全。
- REST API 的资源、方法、状态码和错误格式。

## 运行

```powershell
docker compose --profile web up -d web
```

访问 <http://localhost:8080>，提交姓名并观察 URL、请求方法和转义结果。

## 必做练习

1. 用 POST 替代 GET，并通过浏览器 Network 面板观察差异。
2. 输入 `<script>alert(1)</script>`，确认页面只显示文字。
3. 增加 CSRF Token 和 Session。
4. 建立 `/api/products` JSON 接口，正确使用 200、201、400 和 404。

## 验收

能从“不可信输入”一路说明验证、业务处理、持久化和输出转义的位置。
