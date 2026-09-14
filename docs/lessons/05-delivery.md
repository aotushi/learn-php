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

预期：三项均成功。练习：为 ProductInput 的库存上限再补一个测试。提示：先检查 2147483647 与 2147483648。验收：知道语法通过、单元通过、接口通过是三个不同证据，不互相替代。

<a id="day-26"></a>

## 第 26 天：一条索引、一个事务

索引帮助查找，不保证所有条件都使用它；EXPLAIN 是解释执行计划，不是执行耗时基准。事务把多个写入作为一个整体，失败时回滚。

```powershell
# 显示 SKU 查询执行计划，关注 key 和 rows
docker compose exec php php stages/04-data-storage/exercises/explain.php
# 新增临时商品后故意失败；回滚只涉及本次事务
docker compose exec php php stages/04-data-storage/exercises/transaction.php
```

预期：索引查询有计划；事务显示 Updated rows: 1、Remaining test rows: 0。读 [事务示例](../../stages/04-data-storage/exercises/transaction.php)。练习：在纸上划出事务开始、写入、失败、回滚四步。提示：不要把抛异常等同于自动回滚。验收：说明为何真实订单创建/扣库存更复杂，不能用本例宣称解决并发库存。

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

## 第 29 天：独立完成一个小改动

不看完整答案，为商品增加“默认筛选启用/停用”的页面筛选。先在内存过滤已有列表，后台分页/搜索作为扩展。复用 computed 派生列表，不额外维护第二份可写商品数据。

```powershell
# 构建页面，确认改动能编译
docker compose --profile frontend run --rm node npm run build
```

预期：选择状态改变列表，不会改数据库中的 status。练习：分别看两种状态、空列表和重新刷新后的结果。提示：从 ProductTable 的 products 输入向上找到唯一数据来源。验收：能解释这是前端筛选，不是假装数据库已经支持分页查询。

<a id="day-30"></a>

## 第 30 天：五分钟演示与复述

从 [验收清单](../../stages/09-admin-capstone/README.md) 开始：登录 admin、新增自己商品、编辑、刷新、删除、切换 viewer、演示错误。再不看资料解释一次 Vue → HTTP → 路由 → 验证 → SQL → JSON。

预期：别人按 README 能运行小示例；已有大型后台的适配情况单独说明。练习：列出三个仍不熟悉的点，每个给一个下一步小实验。提示：不会的点不是失败证据，夸大完成才会影响后续学习。验收：代码、测试、演示和限制四件都有。第一阶段结束；按需进入 [Magento 阶段](../../stages/10-magento-foundation/README.md)，不要直接把本地演示部署公网。
