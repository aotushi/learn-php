# 阶段 11：Magento 模块开发

本目录是第二阶段的素材与目标提纲，未完成完整安装和运行验收。先完成 [学习手册主线](../../docs/README.md)，准备边界见 [Magento 选读](../../docs/README.md#magento)。下列目标不属于第一阶段必修。

这是第二阶段，不要求现在运行。命令使用 `-f`（`--file`）选择根目录的独立 Magento 配置，先完成手册中的第二阶段准备。三个 PHP 文件已补充模块注册、格式化、继承、依赖注入与命令返回值的就地说明；PHP 语法回查 [代码阅读速查](../../docs/README.md#php-reference)。有注释不代表已经在完整 Magento 中验证安装。

`Acme/Learning` 是最小可用模块骨架，安装后增加命令：

```powershell
# 递归复制 Acme 模块目录到 Magento 的 app/code；Recurse 表示包含子目录
Copy-Item -Recurse stages/11-magento-module/Acme stages/10-magento-foundation/app/app/code/

# 在 magento-php 服务中启用 Acme_Learning 模块
docker compose -f compose.magento.yaml exec magento-php php bin/magento module:enable Acme_Learning

# 让 Magento 应用模块新增的配置和数据库变更；upgrade 表示升级
docker compose -f compose.magento.yaml exec magento-php php bin/magento setup:upgrade

# 运行本学习模块提供的命令，并把姓名传给 --name 选项
docker compose -f compose.magento.yaml exec magento-php php bin/magento learning:greet --name="Magento learner"
```

`bin` 常用于存放可执行程序（binary），`module:enable` 和 `setup:upgrade` 是 Magento 的“命令组:动作”写法。完整字段说明见 [命令与缩写词典](../../docs/README.md#commands)。

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
