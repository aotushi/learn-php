# 阶段 08：Magento 模块开发

`Acme/Learning` 是最小可用模块骨架，安装后增加命令：

```powershell
Copy-Item -Recurse stages/08-magento-module/Acme stages/07-magento-foundation/app/app/code/
docker compose exec magento-php php bin/magento module:enable Acme_Learning
docker compose exec magento-php php bin/magento setup:upgrade
docker compose exec magento-php php bin/magento learning:greet --name="Magento learner"
```

## 学习顺序

1. 模块注册、版本与依赖关系。
2. 构造函数依赖注入和 `di.xml`。
3. 声明式 Schema、Data Patch、Model、Resource Model 和 Collection。
4. Service Contract 与 Repository。
5. Event/Observer、Plugin、Preference 的选择边界。
6. 后台菜单、ACL、系统配置和 Admin UI。
7. 前台路由、Layout、Block/ViewModel、Template。
8. REST、GraphQL、Cron、消息队列与测试。

## 模块项目要求

把示例扩展为“客户采购申请”模块：

- 后台可以启停并设置最小金额。
- 登录客户可以创建、查看自己的申请。
- 管理员按权限查看和变更状态。
- 提供 REST 或 GraphQL 查询。
- 状态变化通过队列异步记录通知任务。
- 包含单元和集成测试、安装说明及回滚说明。

## 扩展点原则

- 优先使用公开 Service Contract 和事件。
- Plugin 只拦截公开方法，避免复杂嵌套和改变隐藏状态。
- 只有确实需要整体替换时才用 Preference。
- 不修改 `vendor`，不复制大段核心类。
