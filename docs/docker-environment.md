# Docker 环境手册

## 为什么分成多个 Profile

PHP 语法练习只需要一个轻量容器；Magento 完整环境会同时占用数据库、搜索、缓存和队列资源。因此按需启动：

| 命令 | 启动内容 |
| --- | --- |
| `docker compose up -d php` | PHP、Composer、PHPUnit |
| `docker compose --profile web up -d` | PHP 加内置 Web 示例 |
| `docker compose --profile data up -d` | PHP、MariaDB、Valkey |
| `docker compose --profile frontend up -d` | PHP、Node.js |
| `docker compose --profile magento up -d` | Magento 所需完整服务 |

## 版本选择

本项目以 Magento Open Source 2.4.8-p5 为目标。Adobe 官方兼容表列出的关键版本包括 PHP 8.4/8.3、Composer 2.10、OpenSearch 3、MariaDB 11.4/11.8、RabbitMQ 4.2、Valkey 8.1 和 nginx 1.28。本项目选择其中较适合本地学习的一组固定主次版本。

官方资料：

- [Magento 系统要求](https://experienceleague.adobe.com/en/docs/commerce-operations/installation-guide/system-requirements)
- [Magento Open Source 2.4.8 发布说明](https://experienceleague.adobe.com/en/docs/commerce-operations/release/notes/magento-open-source/2-4-8)
- [获取 Magento 软件](https://experienceleague.adobe.com/en/docs/commerce-operations/installation-guide/prerequisites/commerce)

## 常用命令

```powershell
# 查看所有服务，包括尚未启动的 Profile
docker compose --profile web --profile data --profile frontend --profile magento config --services

# 查看状态与日志
docker compose --profile magento ps
docker compose logs --tail 100 php
docker compose logs --tail 100 mariadb

# 在 PHP 容器运行单个文件
docker compose exec php php stages/01-php-syntax/exercises/order_total.php

# 开启 Xdebug 执行一次命令
docker compose exec -e XDEBUG_MODE=debug php php your-script.php

# 打开数据库命令行
docker compose exec mariadb mariadb -umagento -pmagento-local-only learning

# 打开 Valkey 命令行
docker compose exec valkey valkey-cli
```

## 安装 Magento

1. 在 Adobe Commerce Marketplace 创建 Access Keys。
2. 复制 `.env.example` 为 `.env`，填入 `MAGENTO_PUBLIC_KEY` 和 `MAGENTO_PRIVATE_KEY`。
3. 保证 Docker Desktop 至少可使用约 6 GB 内存。
4. 运行安装脚本：

```powershell
powershell -ExecutionPolicy Bypass -File scripts/setup-magento.ps1
```

安装完成后：

- 店铺：<http://localhost:8088>
- 后台：<http://localhost:8088/admin>
- RabbitMQ 管理页：<http://localhost:15672>
- OpenSearch：<http://localhost:9200>

本地默认账户在 `.env` 中。它们只适合本机学习，不能用于公网环境。

## Magento 常用命令

```powershell
docker compose exec magento-php php bin/magento module:status
docker compose exec magento-php php bin/magento cache:status
docker compose exec magento-php php bin/magento cache:flush
docker compose exec magento-php php bin/magento indexer:status
docker compose exec magento-php php bin/magento indexer:reindex
docker compose exec magento-php php bin/magento cron:run
```

## 故障排查

### OpenSearch 无法启动

先看日志：

```powershell
docker compose logs --tail 200 opensearch
```

常见原因是 Docker Desktop 内存不足。关闭不需要的容器并提高 Docker 内存后重试。

### 端口占用

本项目默认使用 8080、8088、13307、6380、9200、5672 和 15672。都可以在 `.env` 中修改。用下面的命令查占用者：

```powershell
Get-NetTCPConnection -State Listen | Where-Object LocalPort -In 8080,8088,13307,6380,9200,5672,15672
```

### 修改初始化 SQL 后没有生效

初始化脚本只在第一次创建数据库卷时运行。若确认不再需要已有学习数据，可以执行 `docker compose down -v` 后重新启动。此操作会永久删除本项目容器卷中的数据库、缓存、搜索索引和队列数据，执行前先导出需要保留的内容。

### Magento 下载失败

- `401`：检查 Marketplace 公钥和私钥。
- 版本不存在：检查 `.env` 中的 `MAGENTO_VERSION`。
- 超时：重新执行脚本；Composer 会复用已下载缓存。
