# PHP 与 Magento 学习项目

这是一个可直接用 Docker 学习的分阶段项目，目标不是只记 PHP 语法，而是逐步具备维护和二次开发 Magento 外贸电商网站的能力。

学习主线：

> PHP 基础 → 面向对象与工程化 → Web、数据库与安全 → Linux 与前端协作 → Magento 架构 → 模块开发 → 搜索、缓存、队列、性能与升级 → B2C 综合项目

## 当前环境版本

| 组件 | 版本 | 用途 |
| --- | --- | --- |
| PHP | 8.4 | PHP 练习与 Magento 运行时 |
| Composer | 2.10 | PHP 依赖与自动加载 |
| MariaDB | 11.4 | 关系数据库 |
| Valkey | 8.1 | Redis 兼容的缓存与会话服务 |
| OpenSearch | 3 | Magento 商品搜索 |
| RabbitMQ | 4.2 | 异步消息队列 |
| nginx | 1.28 | Magento Web 入口 |
| Node.js | 22 | 前端基础练习 |
| Magento Open Source | 2.4.8-p5 | Magento 学习目标版本 |

这些版本按 Magento Open Source 2.4.8-p5 的官方兼容范围选择。Magento 2.4.8 已不再支持 Elasticsearch，因此本项目学习它的继任者 OpenSearch，同时保留两者的概念对照。

## 5 分钟开始

如果已经忘记 Docker，先阅读 [Docker 轻量伴学](docs/docker-light-guide.md)。只需约 10 分钟，并且可以在学习 PHP 时逐步掌握，不必先系统学完 Docker。

先构建并启动轻量 PHP 容器：

```powershell
cd E:\code\github\learn-php
Copy-Item .env.example .env
docker compose up -d php
docker compose exec php php --version
docker compose exec php php stages/00-environment/hello.php
```

安装学习项目的 Composer 依赖并运行自动测试：

```powershell
docker compose exec php composer install
docker compose exec php composer test
```

启动 Web 基础练习后访问 <http://localhost:8080>：

```powershell
docker compose --profile web up -d web
```

启动数据库和缓存练习环境：

```powershell
docker compose --profile data up -d
docker compose --profile data ps
```

进入容器执行练习：

```powershell
docker compose exec php bash
```

停止环境不会删除学习数据：

```powershell
docker compose --profile web --profile data --profile frontend --profile magento down
```

> 不要随意添加 `-v`。`docker compose down -v` 会删除本地数据库等学习数据。

## 学习顺序

| 阶段 | 目录 | 建议时间 | 完成标志 |
| --- | --- | ---: | --- |
| 00 | `stages/00-environment` | 1–2 天 | 会用 Docker 执行 PHP、Composer 和测试 |
| 01 | `stages/01-php-syntax` | 2 周 | 能独立完成订单金额计算等命令行练习 |
| 02 | `stages/02-oop-composer` | 2 周 | 掌握类、接口、异常、命名空间和自动加载 |
| 03 | `stages/03-web-security` | 2 周 | 理解 HTTP、表单、会话、验证与常见安全问题 |
| 04 | `stages/04-data-storage` | 2 周 | 会写 SQL、事务、索引，并使用缓存 |
| 05 | `stages/05-engineering` | 2 周 | 会测试、调试、重构和阅读陌生项目 |
| 06 | `stages/06-linux-frontend` | 2 周 | 会部署排障，并能与 Vue/React 前端协作 |
| 07 | `stages/07-magento-foundation` | 3 周 | 能安装、配置和解释 Magento 核心结构 |
| 08 | `stages/08-magento-module` | 4 周 | 能完成模块、数据、后台配置和 API 开发 |
| 09 | `stages/09-commerce-operations` | 3 周 | 会处理缓存、索引、队列、搜索、性能和升级 |
| 10 | `stages/10-capstone` | 3 周 | 完成一个可演示、可测试的 B2C 功能闭环 |

完整的每周安排、练习和验收标准见 [学习路线](docs/learning-path.md)。岗位要求与各阶段的对应关系见 [岗位能力映射](docs/job-skill-map.md)。

## Magento 环境说明

Magento 代码由官方 Composer 仓库分发，下载时需要免费的 Adobe Commerce Marketplace 公钥和私钥。项目不会保存真实密钥；取得密钥后填入本地 `.env`，再运行：

```powershell
powershell -ExecutionPolicy Bypass -File scripts/setup-magento.ps1
```

脚本会下载 Magento 2.4.8-p5、启动所有依赖、完成本地安装并输出访问地址。详细说明和常见问题见 [Docker 环境手册](docs/docker-environment.md)。

## 日常节奏

建议每天 60–90 分钟：20 分钟阅读、40 分钟动手、10 分钟记录。每阶段都按“学习 → 小练习 → 阶段项目 → 自测”推进。不要在未做练习时连续阅读大量章节。

学习记录可以直接写在 [进度清单](docs/progress-checklist.md) 中。所有命令默认在本项目根目录执行。
