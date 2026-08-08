# Docker 环境手册

如果命令中的字段或缩写不熟悉，先查 [命令与缩写词典](command-glossary.md)。代码块中 `#` 开头的是可安全复制的解释性注释。

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
# 启用所有配置组，解析配置并只列出服务名；不会启动服务
docker compose --profile web --profile data --profile frontend --profile magento config --services

# 显示 Magento 配置组的容器状态；ps = process status
docker compose --profile magento ps

# 查看 php 服务最后 100 行日志
docker compose logs --tail 100 php

# 查看 mariadb 服务最后 100 行日志
docker compose logs --tail 100 mariadb

# 在 php 服务中用 PHP 程序运行订单金额示例
docker compose exec php php stages/01-php-syntax/exercises/order_total.php

# 仅为这一次执行设置环境变量，开启 Xdebug 调试模式；-e = --env
docker compose exec -e XDEBUG_MODE=debug php php your-script.php

# 在 mariadb 服务中打开数据库客户端：-u 指用户，-p 后面是本地密码
docker compose exec mariadb mariadb -umagento -pmagento-local-only learning

# 在 valkey 服务中打开 Valkey 命令行客户端；CLI = Command-Line Interface
docker compose exec valkey valkey-cli
```

## 安装 Magento

1. 在 Adobe Commerce Marketplace 创建 Access Keys。
2. 复制 `.env.example` 为 `.env`，填入 `MAGENTO_PUBLIC_KEY` 和 `MAGENTO_PRIVATE_KEY`。
3. 保证 Docker Desktop 至少可使用约 6 GB 内存。
4. 运行安装脚本：

```powershell
# `-ExecutionPolicy Bypass` 仅为本次运行绕过本机脚本限制；`-File` 指定脚本文件
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
# 查看已经启用和停用的 Magento 模块
docker compose exec magento-php php bin/magento module:status

# 查看每一种 Magento 缓存的状态
docker compose exec magento-php php bin/magento cache:status

# 清空 Magento 缓存；flush 表示“清空”
docker compose exec magento-php php bin/magento cache:flush

# 查看索引器状态；indexer 表示“索引器”
docker compose exec magento-php php bin/magento indexer:status

# 重建全部索引；reindex 表示“重新建立索引”
docker compose exec magento-php php bin/magento indexer:reindex

# 手工执行一次定时任务；Cron 是类 Unix 系统的定时任务机制
docker compose exec magento-php php bin/magento cron:run
```

## 故障排查

### OpenSearch 无法启动

先看日志：

```powershell
# 查看 opensearch 服务最后 200 行日志
docker compose logs --tail 200 opensearch
```

常见原因是 Docker Desktop 内存不足。关闭不需要的容器并提高 Docker 内存后重试。

### 端口占用

本项目默认使用 8080、8088、13307、6380、9200、5672 和 15672。都可以在 `.env` 中修改。用下面的命令查占用者：

```powershell
# 获取正在监听的 TCP 端口，再只保留本项目关心的端口
Get-NetTCPConnection -State Listen | Where-Object LocalPort -In 8080,8088,13307,6380,9200,5672,15672
```

### 修改初始化 SQL 后没有生效

初始化脚本只在第一次创建数据库卷时运行。若确认不再需要已有学习数据，可以执行 `docker compose down -v` 后重新启动。此操作会永久删除本项目容器卷中的数据库、缓存、搜索索引和队列数据，执行前先导出需要保留的内容。

### Magento 下载失败

- `401`：检查 Marketplace 公钥和私钥。
- 版本不存在：检查 `.env` 中的 `MAGENTO_VERSION`。
- 超时：重新执行脚本；Composer 会复用已下载缓存。
