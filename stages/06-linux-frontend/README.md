# 阶段 06：Linux、部署与前端协作

## Linux 与部署

在 PHP 容器内练习：

```powershell
docker compose exec php bash
pwd
ls -la
ps aux
ip addr
ss -lntp
df -h
free -h
php --ini
```

重点理解文件权限、进程、信号、端口、DNS、日志、Cron、nginx 与 PHP-FPM 的关系。

## Vue 前端小项目

```powershell
docker compose --profile frontend up -d node
docker compose exec node npm install
docker compose exec node npm run build
docker compose exec node npm run dev -- --host 0.0.0.0
```

当前 Node 容器未映射开发端口，建议先完成 `npm run build`，再自行给 `node` 服务添加端口映射作为 Docker 练习。

## 必做练习

1. 用浏览器 Network 面板分析一个请求的 URL、方法、状态码和耗时。
2. 修改 Vue 商品列表，增加加载中、空数据和错误状态。
3. 分别从浏览器、nginx、PHP、MariaDB 日志定位一次失败请求。
4. 写一个每 5 分钟运行的示例 Cron，并说明如何避免重复执行。

## 验收

能画出浏览器到数据库的请求链路，并指出每一层应该查看什么证据。
