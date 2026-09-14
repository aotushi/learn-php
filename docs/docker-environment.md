# Docker 环境手册

日常跟随 [轻量伴学](docker-light-guide.md)。命令字段随查 [词典](command-glossary.md)。所有服务只绑定 127.0.0.1；不要把带默认密码和开发服务器的项目发布公网。

## 按需启动

| 场景 | 准备方式 | 服务 |
| --- | --- | --- |
| PHP 语法 | docker compose up -d php | PHP 工具容器 |
| 原生 HTTP | docker compose --profile web up -d web | PHP 网页示例 |
| MySQL | docker compose --profile data up -d --wait php mysql | PHP + MySQL，不启动缓存 |
| ThinkPHP | setup-thinkphp.ps1 | 安装锁定依赖、学习表、API 健康检查 |
| Vue 对照示例 | setup-thinkphp.ps1 -WithFrontend | 上述服务 + Vue |
| 缓存扩展 | cache profile | Valkey，第一月选做 |
| Magento | 阶段 10 安装脚本 | 独立第二阶段 |

profile 是配置组。首次使用 ThinkPHP/Vue 不要跳过安装脚本；单独 up 不会替你安装 Composer/npm 依赖。

## 第一阶段准备与检查

```powershell
# 完整本机对照示例；不需要 Vue 时省略 -WithFrontend
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1 -WithFrontend
# 查看本项目服务状态
docker compose ps
# 验证应用与数据库连接；不需要登录
Invoke-RestMethod http://localhost:18081/api/health
```

预期：应用健康，页面 http://localhost:15173 可登录。初始 admin/viewer 密码均为 LearnPhp123!；重复初始化不重置密码。API 在 18081；商品接口需要令牌，按 [手动练习](api-workshop.md) 登录。MySQL 宿主机端口 13307，容器间用 mysql:3306。

脚本检查 Docker 可用性，用退出码处理原生命令结果，兼容 Windows PowerShell 5.1；缺镜像时构建。--wait 结合 API 健康检查，不只检查容器进程存在。首次下载较慢，别同时启动多个安装过程。

## 数据与依赖

- 本机源码通过绑定挂载共享，改 PHP 无需构建镜像。
- MySQL 使用 mysql84-data 命名卷；旧 MariaDB 卷保留，未自动删除或导入。
- Node 使用 frontend-modules 卷，避免 Windows/Linux 的原生依赖互相污染。
- PHP 依赖由两个 composer.lock 固定；前端由 package-lock.json 固定。npm ci 会重建前端依赖卷里的 node_modules，不会删除源码。
- 新 MySQL 卷执行 docker/mysql/init.sh；它用 MYSQL_DATABASE/MYSQL_USER 选择数据库及授权。名称只用英文字母、数字、下划线。
- 已有卷由 scripts/setup-data.php 补缺少的表和种子，不删除/覆盖已有商品或密码。CREATE TABLE IF NOT EXISTS 不会升级已有表结构。
- 修改 .env 的数据库名/用户/密码不能自动改变已有 MySQL 账户。需要专门的迁移/授权步骤；不要用 down -v 解决连接失败。

## 日志分三类

```powershell
# CLI：脚本输出在当前终端
docker compose exec php php stages/01-php-syntax/exercises/day05.php
# Web：ThinkPHP 开发服务器的访问记录
docker compose logs --tail 50 thinkphp
# 应用：列出详细错误日志目录，按日期打开需要的文件
Get-ChildItem stages/07-thinkphp-foundation/app/runtime/log -Recurse -File
```

php 容器主进程是 sleep，docker compose logs php 为空通常正常；exec 的脚本错误不会自动进入它。500 的堆栈保存在应用日志，API 只返回通用错误，不把数据库细节送给浏览器。

## 常见问题

Docker API 无法连接：打开 Docker Desktop，等待引擎就绪。数据库未健康：查看 mysql 日志。缺 vendor/autoload.php：重跑安装脚本。Vue 空白：查浏览器控制台与 frontend 日志；只有 build 成功不够。

```powershell
# 查看 Docker 客户端和引擎版本；只有客户端信息说明引擎未就绪
docker version
# 查看本项目全部容器，包括停止的
docker compose ps -a
# 查看数据库最近输出
docker compose logs --tail 50 mysql
# 查看前端开发服务最近输出
docker compose logs --tail 50 frontend
```

端口占用时修改 .env 中本项目端口，不关闭别的项目。常用默认端口：8080（原生网页）、18081（ThinkPHP）、15173（Vue）、13307（MySQL）。

## 版本与第二阶段

第一阶段 PHP 8.4、MySQL 8.4 LTS、ThinkPHP 核心 8.1.4、Node.js 22。8.4 镜像标签会接收同系列补丁。锁定依赖不等于永远不更新；更新后重跑测试。

第二阶段保留 Magento 2.4.8-p5 配置，额外用 Valkey/OpenSearch/RabbitMQ/nginx。安装前核对 [Adobe 系统要求](https://experienceleague.adobe.com/en/docs/commerce-operations/installation-guide/system-requirements) 与 Marketplace 密钥，再按 [阶段 10](../stages/10-magento-foundation/README.md) 操作；本次未安装验证 Magento。

官方参考：[ThinkPHP 项目](https://github.com/top-think/think/tree/8.x)、[MySQL LTS 模型](https://dev.mysql.com/doc/refman/8.4/en/mysql-releases.html)、[Compose up 与 wait](https://docs.docker.com/reference/cli/docker/compose/up/)、[Vue 构建工具](https://vuejs.org/guide/scaling-up/tooling.html)。
