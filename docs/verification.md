# 本次修订验收记录

日期：2026-09-15。范围：第一阶段教程、Docker 启动、小型 Vue 对照示例、ThinkPHP 商品 API。没有修改已有 resume 前端；没有安装或验证第二阶段 Magento。

## 已实测

- Windows PowerShell 5.1 安装脚本：后端与 -WithFrontend 两种方式均成功，重复运行保留现有记录。
- PHP 8.4.24、ThinkPHP 核心 8.1.4、MySQL 8.4.11。
- PHPUnit：7 个测试、24 个断言通过。
- API 集成检查：59 个断言通过，包含无登录、错误密码、只读权限、无效输入、创建/修改/删除、网址归属、退出撤销和过期令牌。
- PHP 语法检查：58 个文件通过。
- 第 2–7、9 天 CLI 示例实际运行，输出与教程一致。
- 原生 PHP /products.php 返回数据库商品；事务示例回滚后临时行数 0；SKU 查询命中唯一索引。
- 浏览器使用真实 Chrome 无头模式：登录、实际商品列表、新增、编辑、刷新、重复 SKU 错误、删除、viewer 只读、服务器 403、离线错误/恢复、服务器撤销会话后回到登录均通过。没有页面 JavaScript 异常。
- 空列表 UI 分支用单独拦截响应验证，之后恢复真实请求；它不算真实删空数据库测试。
- Vue 构建通过；nanoid 从 3.3.17 更新到 3.3.19 后 npm audit 报告 0 个已知漏洞（这是当次结果，不是永久安全保证）。
- 新建隔离 MySQL 临时容器，使用 custom_learning / custom_student 初始化，成功生成两条种子及正确授权。不复用主学习数据卷。
- 文档命令注释、本地链接/每日锚点、Git 空白错误检查通过；所有 Compose 配置组可解析。
- 临时数据库验证容器已清理；主学习数据和旧 MariaDB 数据卷没有删除。接口和浏览器测试只清理自己创建的临时记录。

## 可复查的入口

```powershell
# 检查文档中的命令注释
python .agents/skills/teach-php-fullstack/scripts/check_tutorial_docs.py README.md docs stages
# 检查仓库内文档链接和每日锚点
node scripts/check-docs.mjs
# 检查业务规则
docker compose exec php composer test
# 检查真实 API 和数据库操作
docker compose exec php php scripts/api-smoke.php
```

scripts/browser-smoke.cjs 是可选维护者检查，需本机安装 Chrome 且 Node 能解析 playwright；本次使用桌面应用预装的 Playwright，不把它加入初学者必装依赖。普通学习者按第 19–24 天在浏览器手动完成同样的流程即可。

## 尚未宣称完成的内容

已有大型 Vue 后台仍需按 [适配说明](vue-admin-integration.md) 单独改造、验证；小示例通过不代表原后台全站兼容。学习成果仍需学习者亲自完成练习并勾选进度。本地认证未包含公网限流、审计、完整账号生命周期、HTTPS 部署等生产要求。
