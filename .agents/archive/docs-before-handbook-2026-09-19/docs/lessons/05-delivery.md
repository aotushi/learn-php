# 第 25–30 天：巩固与交付

目标：能独立解释、修改、验证一个小后台；不是在最后几天硬塞完整商城。订单、分类、库存流水、缓存、队列和 Magento 都不是本月验收条件。

<a id="day-25"></a>

## 第 25 天：完整回归

```powershell
# 验证独立业务规则
docker compose exec php composer test
# 检查 PHP 文件语法
docker compose exec php composer lint
# 验证真实 HTTP、登录、权限和数据库操作
docker compose exec php php scripts/api-smoke.php
```

预期：三项均成功；它们是参考功能检查，不是个人掌握证明。独立练习：为 ProductInput 的库存上限再补一个测试，先预测 2147483647 与 2147483648；并运行自己在第 18 天写的详情/权限检查。验收：知道语法通过、单元通过、接口通过是三个不同证据，不互相替代，自己的新增功能必须有自己的检查。

<a id="day-26"></a>

## 第 26 天：一条索引、一个事务

索引帮助查找，不保证所有条件都使用它；EXPLAIN 是解释执行计划，不是执行耗时基准。事务把多个写入作为一个整体，失败时回滚。

先认源码中的动作：`beginTransaction` 开始，`inTransaction` 判断是否仍在事务中，`rollBack` 撤销；正常成功才使用 `commit` 提交，本示例故意失败所以没有提交。`rowCount` 是更新影响的行数，`fetchColumn` 在这里取回滚后的计数，两者不是库存数量。其他写法查 [PHP 阅读速查](../php-reading-guide.md)。

```powershell
# 显示 SKU 查询执行计划，关注 key 和 rows
docker compose exec php php stages/04-data-storage/exercises/explain.php
# 新增临时商品后故意失败；回滚只涉及本次事务
docker compose exec php php stages/04-data-storage/exercises/transaction.php
```

预期：索引查询有计划；事务显示 Updated rows: 1、Remaining test rows: 0。读 [事务示例](../../stages/04-data-storage/exercises/transaction.php)，然后遮住参考实现，完成 [数据关卡](../practice-checkpoints.md#checkpoint-data) 的两次临时写入：分别在第二次写入前、后失败，先预测再确认回滚后均无残留。提示：不要把抛异常等同于自动回滚；只使用自己新建的随机 SKU，不改既有商品。验收：能自己实现并解释事务边界；真实订单创建/扣库存更复杂，不能用本例宣称解决并发库存。

<a id="day-27"></a>

## 第 27 天：小步重构，而不是抽象竞赛

看 [ProductInput](../../stages/07-thinkphp-foundation/app/app/service/ProductInput.php)，选择一个重复判断提取为私有函数，保证输入输出不变。只做这一处；不要同时重排全部控制器。

```powershell
# 重构前后都运行同一套测试，比较结果
docker compose exec php composer test
```

预期：前后都通过。练习：写两句“为什么提取/为什么不提取”。提示：测试只能覆盖已有用例，先增加被改分支的边界测试。验收：改动能用一句话说明，且无新功能混入。

<a id="day-28"></a>

## 第 28 天：恢复环境，不删除数据

记录一件自己创建商品的 SKU。停止本项目三个应用容器，再按脚本恢复；不要运行 down -v。

```powershell
# 只停止本项目应用，不删除容器或 MySQL 数据卷
docker compose stop frontend thinkphp php
# 重复准备环境并检查健康，保留已有记录
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1 -WithFrontend
```

预期：重新登录后商品还在。练习：查出源码、前端依赖、MySQL 数据分别存在哪里。提示：源码绑定挂载、前端依赖独立命名卷、数据库 mysql84-data 卷。验收：区别于删库重来；已存在数据库不能只靠修改 .env 就更换账号。

<a id="day-29"></a>

## 第 29 天：独立实现 PHP 服务端筛选

完成 [服务端筛选独立题](../exercises/server-filter.md)：只扩展现有商品列表接口，支持状态和最低价格两个条件。验证输入后在数据库中筛选，保持原登录、权限与返回格式；不重写 Vue 页面，不加分页或搜索。

```powershell
# 回归已有业务规则；还必须运行自己编写的新筛选检查
docker compose exec php composer test
# 确认原有登录、权限与商品操作没有因新筛选退化
docker compose exec php php scripts/api-smoke.php
```

本题尚未在参考项目实现；以上已有测试全绿也不证明筛选完成。验收：按题目自己的三个商品核对实际行内容，覆盖两个条件同时成立、等于门槛、无匹配项、非法参数、viewer 读取、无令牌和无参数兼容。提示：先预测，再验证，不先强制转整数。只清理自己创建的数据。

<a id="day-30"></a>

## 第 30 天：五分钟演示与复述

从 [验收清单](../../stages/09-admin-capstone/README.md) 开始：登录 admin、新增自己商品、编辑、刷新、删除、切换 viewer、演示错误。再不看资料解释一次 Vue → HTTP → 路由 → 验证 → SQL → JSON。

预期：别人按 README 能运行小示例；已有大型后台的适配情况单独说明。复述 [五个核心问题](../core-knowledge.md)，把昨天最低价筛选换成最大库存，在纸上独立写出规则与测试，不重看筛选答案。对照 [进度清单](../progress-checklist.md) 区分跟做与掌握；没有通过的核心关卡继续巩固，不为了日期跳过。验收：代码、自己的检查、演示和限制四件都有。第一阶段结束后按需进入 [Magento 阶段](../../stages/10-magento-foundation/README.md)，不要直接把本地演示部署公网。
