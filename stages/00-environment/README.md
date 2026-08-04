# 阶段 00：环境与工具

如果 Docker 已经忘得差不多，先做 [Docker 轻量伴学](../../docs/docker-light-guide.md) 中的 10 分钟练习。本阶段只要求会启动、执行、查看和停止，不要求理解 Docker 的全部原理。

## 目标

- 理解镜像、容器、挂载目录、端口和数据卷。
- 会在容器内执行 PHP、Composer 和 PHPUnit。
- 会查看容器状态、日志与资源占用。

## 开始

```powershell
docker compose up -d php
docker compose exec php php --version
docker compose exec php composer --version
docker compose exec php php stages/00-environment/hello.php
docker compose ps
docker compose logs --tail 20 php
```

## 必做练习

1. 修改 `hello.php` 的名字，再运行一次，观察本机文件如何立即出现在容器中。
2. 在容器中运行 `pwd`、`ls -la`、`ps aux`、`php -m`。
3. 解释停止容器与删除容器的区别，解释数据卷为何适合数据库。

## 验收

- 不进入 Docker Desktop 界面也能启动、检查和停止环境。
- 遇到命令失败时，先看状态和日志，而不是反复重装。
