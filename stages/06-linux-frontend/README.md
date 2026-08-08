# 阶段 06：Linux、部署与前端协作

## Linux 与部署

在 PHP 容器内练习：

```powershell
# 进入 php 容器的 Bash 命令行；Bash = Bourne Again Shell
docker compose exec php bash

# 显示当前目录；pwd = print working directory
pwd

# 详细列出全部文件；ls = list，-l = long，-a = all
ls -la

# 显示几乎所有进程；ps = process status
ps aux

# 显示网络地址；IP = Internet Protocol，addr = address
ip addr

# 显示正在监听的 TCP 端口及进程；ss = socket statistics
ss -lntp

# 以易读单位显示磁盘空间；df = disk free，-h = human-readable
df -h

# 以易读单位显示内存；-h = human-readable
free -h

# 显示 PHP 当前使用的配置文件；ini = initialization file
php --ini
```

重点理解文件权限、进程、信号、端口、DNS、日志、Cron、nginx 与 PHP-FPM 的关系。

## Vue 前端小项目

```powershell
# 启用 frontend 配置组，在后台启动 node 服务
docker compose --profile frontend up -d node

# 在 node 服务中安装 package.json 声明的前端依赖
docker compose exec node npm install

# 执行 package.json 中名为 build 的生产构建脚本
docker compose exec node npm run build

# 执行开发服务器；`--` 后的参数传给 Vite，0.0.0.0 允许容器外访问
docker compose exec node npm run dev -- --host 0.0.0.0
```

`npm` 是命令名称，官方名称不是缩写；`dev` 是 `development`（开发）的常见缩写。完整字段说明见 [命令与缩写词典](../../docs/command-glossary.md)。

当前 Node 容器未映射开发端口，建议先完成 `npm run build`，再自行给 `node` 服务添加端口映射作为 Docker 练习。

## 必做练习

1. 用浏览器 Network 面板分析一个请求的 URL、方法、状态码和耗时。
2. 修改 Vue 商品列表，增加加载中、空数据和错误状态。
3. 分别从浏览器、nginx、PHP、MariaDB 日志定位一次失败请求。
4. 写一个每 5 分钟运行的示例 Cron，并说明如何避免重复执行。

## 验收

能画出浏览器到数据库的请求链路，并指出每一层应该查看什么证据。
