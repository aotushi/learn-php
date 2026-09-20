# 30 天全日制学习路线

目标：一个可解释、可测试的商品管理功能。第 12 天让 PHP 从数据库返回 JSON，第 18 天掌握 ThinkPHP 商品接口，第 24 天完成小示例联调，第 30 天完成复现和演示。Magento 留在第二阶段。

先读 [五个核心问题](core-knowledge.md)，区分“必须独立掌握”和“会用、能查即可”。每天可用 6 小时，建议把大部分动手时间留给独立变式和验证，而不是均匀分配给新名词；具体节奏见该页。已经熟悉的前端内容通过联调验证后可以缩短，把时间还给 PHP 和数据库。核心关卡未过就继续练，不靠压缩基础强行赶进度。

第 12 天核对请求、输入和基本查询；第 18 天独立实现一个小操作并写失败检查；第 22 天验证服务端权限；第 26 天独立解释并复现回滚；第 29–30 天以新 PHP 需求验证迁移能力。对应 [五个独立关卡](practice-checkpoints.md) 替换当天部分跟做练习，不增加总任务量。

## 每日入口

每页包含具体解释、可运行示例、预期结果和一个小练习。每次只读当前日的小节。

| 天 | 当日重点 | 教程 |
| --- | --- | --- |
| 1 | 环境与 PHP 进程 | [第 1 天](lessons/01-php.md#day-1) |
| 2 | 变量与类型 | [第 2 天](lessons/01-php.md#day-2) |
| 3 | 条件与循环 | [第 3 天](lessons/01-php.md#day-3) |
| 4 | 数组与 JSON 列表 | [第 4 天](lessons/01-php.md#day-4) |
| 5 | 函数与异常 | [第 5 天](lessons/01-php.md#day-5) |
| 6 | 文件与 JSON 错误 | [第 6 天](lessons/01-php.md#day-6) |
| 7 | 金额小练习 | [第 7 天](lessons/01-php.md#day-7) |
| 8 | HTTP 与 HTML 转义 | [第 8 天](lessons/02-foundations.md#day-8) |
| 9 | 一个基础对象 | [第 9 天](lessons/02-foundations.md#day-9) |
| 10 | Composer 与测试 | [第 10 天](lessons/02-foundations.md#day-10) |
| 11 | MySQL 表与查询 | [第 11 天](lessons/02-foundations.md#day-11) |
| 12 | PDO JSON 接口 | [第 12 天](lessons/02-foundations.md#day-12) |
| 13 | ThinkPHP 入口与路由 | [第 13 天](lessons/03-thinkphp.md#day-13) |
| 14 | 商品输入验证 | [第 14 天](lessons/03-thinkphp.md#day-14) |
| 15 | 创建并查询商品 | [第 15 天](lessons/03-thinkphp.md#day-15) |
| 16 | 修改与删除 | [第 16 天](lessons/03-thinkphp.md#day-16) |
| 17 | 统一错误与权限边界 | [第 17 天](lessons/03-thinkphp.md#day-17) |
| 18 | 真实接口检查 | [第 18 天](lessons/03-thinkphp.md#day-18) |
| 19 | Vue 页面连接 | [第 19 天](lessons/04-integration.md#day-19) |
| 20 | 页面增删改查 | [第 20 天](lessons/04-integration.md#day-20) |
| 21 | 简单登录 | [第 21 天](lessons/04-integration.md#day-21) |
| 22 | 固定读写权限 | [第 22 天](lessons/04-integration.md#day-22) |
| 23 | 已有 Vue 后台适配边界 | [第 23 天](lessons/04-integration.md#day-23) |
| 24 | 浏览器到服务端排错 | [第 24 天](lessons/04-integration.md#day-24) |
| 25 | 回归测试 | [第 25 天](lessons/05-delivery.md#day-25) |
| 26 | 索引与回滚 | [第 26 天](lessons/05-delivery.md#day-26) |
| 27 | 小步重构 | [第 27 天](lessons/05-delivery.md#day-27) |
| 28 | 恢复环境 | [第 28 天](lessons/05-delivery.md#day-28) |
| 29 | PHP 服务端筛选独立题 | [第 29 天](lessons/05-delivery.md#day-29) |
| 30 | 演示与复盘 | [第 30 天](lessons/05-delivery.md#day-30) |

## 必做与选做

必做：PHP 基础、HTTP、基本 OOP/Composer、MySQL/PDO、商品增删改查、验证、简单登录、只读/编辑权限、错误处理、测试、恢复环境。学 Docker 只为运行当前结果，不先补完整 Docker 课程。

选做：复杂折扣策略、枚举、分类、分页、订单事务、并发库存、操作日志、缓存、Xdebug、高级认证。Magento、搜索、消息队列是第二阶段专题。

已有 Vue 后台不是“改地址即完成”；必须按 [适配说明](vue-admin-integration.md) 核对认证、守卫和商品请求。小示例跑通与已有后台适配分别验收。最后标准见 [阶段 09](../stages/09-admin-capstone/README.md)，学习状态记录在 [进度清单](progress-checklist.md)。

技能来源与更新保留在仓库 .agents/skills/teach-php-fullstack；每日教程落实其“先解释、再动手、再观察、再小练习”的规范。

完成标准不是跑完 30 天命令：五个核心模型要有解释、独立实现、失败定位和隔天变式的证据。已有测试通过只证明参考程序被覆盖的行为；第 29 天的筛选是待你实现的任务，不宣称当前演示已经提供。
