# 阶段 00：环境与工具

如果 Docker 已经忘得差不多，先做 [Docker 轻量伴学](../../docs/docker-light-guide.md) 中的 10 分钟练习。本阶段只要求会启动、执行、查看和停止，不要求理解 Docker 的全部原理。

## 目标

- 理解镜像、容器、挂载目录、端口和数据卷。
- 会在容器内执行 PHP、Composer 和 PHPUnit。
- 会查看容器状态、日志与资源占用。

## 开始

下面代码块中的 `#` 开头行是注释，只用于解释，不会被 PowerShell 执行，因此可以整块复制。所有命令默认在项目根目录运行。

```powershell
# 创建并在后台启动名为 php 的服务
docker compose up -d php

# 在 php 服务中运行 PHP 程序，显示 PHP 版本
docker compose exec php php --version

# 在 php 服务中运行 Composer 程序，显示 Composer 版本
docker compose exec php composer --version

# 在 php 服务中用 PHP 执行第一个示例文件
docker compose exec php php stages/00-environment/hello.php

# 列出本项目当前正在运行的容器及其状态
docker compose ps

# 查看 php 服务最后 20 行日志
docker compose logs --tail 20 php
```

## 命令字段拆解

| 字段 | 完整写法或来源 | 这里表示什么 |
| --- | --- | --- |
| `docker` | Docker 客户端 | 把命令发送给 Docker |
| `compose` | Compose，非缩写 | 按本项目的 `compose.yaml` 管理服务 |
| `up` | up，非缩写 | 创建并启动服务 |
| `-d` | `--detach` | 让服务在后台运行 |
| `exec` | `execute` | 在已经运行的服务容器中执行命令 |
| 第一个 `php` | Compose 服务名 | 选择在哪个服务中运行 |
| 第二个 `php` | PHP 程序名 | 选择要运行的程序 |
| `--version` | version | 显示版本信息 |
| `composer` | Composer 程序名 | PHP 的依赖管理工具 |
| `ps` | `process status` | 显示容器状态 |
| `logs` | logs，非缩写 | 查看服务日志 |
| `--tail 20` | tail，末尾 | 只取最后 20 行 |

完整词典见 [命令与缩写词典](../../docs/command-glossary.md)。

## 必做练习

1. 修改 `hello.php` 的名字，再运行一次，观察本机文件如何立即出现在容器中。
2. 在容器中运行 `pwd`、`ls -la`、`ps aux`、`php -m`。
3. 解释停止容器与删除容器的区别，解释数据卷为何适合数据库。

## 验收

- 不进入 Docker Desktop 界面也能启动、检查和停止环境。
- 遇到命令失败时，先看状态和日志，而不是反复重装。
