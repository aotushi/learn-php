# PHP 8.4 + ThinkPHP 8.1：30 天后台开发练习

第一阶段：PHP 8.4、MySQL 8.4 LTS、ThinkPHP 8.1、Vue 后台联调。第二阶段：Magento。目标是独立完成一个小型商品管理功能，不是一个月精通 PHP，也不是从零建设商城。

先读 [30 天路线与每日教程](docs/learning-path.md)。Docker 已经忘记也没关系，跟着 [轻量伴学](docs/docker-light-guide.md) 使用即可；命令字段查 [词典](docs/command-glossary.md)。

## 先运行 PHP

命令均在本仓库根目录的 PowerShell 执行。# 开头的是注释，可以整块复制。

```powershell
# 仅在没有本地配置时复制示例，不覆盖已有端口或密码
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
# 后台启动 PHP 工具容器；-d = --detach
docker compose up -d php
# 第一个 php 是服务名，第二个是容器内的 PHP 程序
docker compose exec php php --version
# 执行第一个文件；结果显示在当前终端
docker compose exec php php stages/00-environment/hello.php
```

预期：PHP 8.4 和问候语。若缺少镜像，Compose 会构建，首次下载可能需数分钟；修改练习源码不必重新构建。

## 运行完整的小示例（第 19 天；也可先预览）

```powershell
# 安装锁定依赖、补齐本机学习表，等待 MySQL、ThinkPHP、Vue 均可用
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1 -WithFrontend
# 检查 API 与数据库健康
Invoke-RestMethod http://localhost:18081/api/health
```

打开 http://localhost:15173。编辑账户 admin、只读账户 viewer，初始密码均为 LearnPhp123!。页面提供登录、商品列表、新增、编辑、删除及错误提示；商品确实保存在 MySQL，不是写死的前端数据。完整页面刷新后需要重新登录。

省略 -WithFrontend 只准备后端。脚本可重复执行，不重置已有商品或账户密码，但会补回缺少的示例种子。没有登录直接请求商品 API 返回 401，这是预期；手动命令见 [接口练习](docs/api-workshop.md)。

示例只是联调对照工具，不替代你的现有后台；已有 Vue 后台需要核对登录、路由守卫、令牌过期和商品契约，不能只改地址。详见 [适配边界](docs/vue-admin-integration.md)。本仓库不会修改 resume 项目。

## 30 天安排

| 天 | 主要结果 | 每日教程 | 对应素材目录 |
| --- | --- | --- | --- |
| 1–7 | PHP 语法和金额小程序 | [第一周](docs/lessons/01-php.md) | 00、01 |
| 8–12 | HTTP、基础对象、Composer、MySQL、PDO JSON | [基础连接](docs/lessons/02-foundations.md) | 02、03、04 |
| 13–18 | ThinkPHP 商品增删改查和验证 | [框架实践](docs/lessons/03-thinkphp.md) | 07、08 |
| 19–24 | Vue、简单登录、固定权限、联调排错 | [前后端联调](docs/lessons/04-integration.md) | 06、08 |
| 25–30 | 测试、事务/索引入门、复现与交付 | [收尾验收](docs/lessons/05-delivery.md) | 04、05、09 |

目录编号按知识主题保留，不代表本次每天的阅读顺序。以每日教程为准。[进度清单](docs/progress-checklist.md) 不预先替学习者打勾。

本月必做：商品增删改查、基本验证、简单登录、读写权限、错误处理、测试、可重复运行。订单、分类、复杂库存、缓存、队列和高级认证是扩展，不作为最后三天的硬要求。

## 自检

```powershell
# 单元测试：验证金额和商品输入边界
docker compose exec php composer test
# PHP 语法检查
docker compose exec php composer lint
# 真实接口检查：创建临时商品，验证后清理自己的测试数据
docker compose exec php php scripts/api-smoke.php
# 编译 Vue；还需要打开浏览器操作，构建不能替代运行验收
docker compose --profile frontend run --rm node npm run build
```

验收见 [阶段 09](stages/09-admin-capstone/README.md)，维护者实测结果见 [修订验收记录](docs/verification.md)。版本、端口、故障处理见 [环境手册](docs/docker-environment.md)。

## 版本与第二阶段

主线固定 PHP 8.4、MySQL 8.4 LTS、ThinkPHP 模板 8.1.3 / 核心 8.1.4（应用锁文件）、Node.js 22。Docker 的 8.4 标签会接收同系列补丁，不表示每台机器补丁号完全相同。PHP 7 是后续旧项目兼容专题，见 [兼容说明](docs/php7-compatibility.md)。

Magento 保留为第二阶段：[10 基础](stages/10-magento-foundation/README.md) → [11 模块](stages/11-magento-module/README.md) → [12 运维](stages/12-magento-operations/README.md) → [13 项目](stages/13-magento-capstone/README.md)。安装前重新核对 Adobe 兼容表与 Marketplace 权限。本次不安装或验证 Magento。

所有端口只绑定本机 127.0.0.1。默认密码、开发服务器和简化登录仅供本地教学，不得直接公开部署。不要运行 docker compose down -v，它会删除本项目数据卷。
