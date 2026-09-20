# ThinkPHP 源码索引

学习说明、命令、练习和完成记录都在 [唯一手册](../../docs/README.md)。本页只把文件对应到同一个学习单元；点击单元号进入具体步骤。

| 学习单元 | 文件 | 本次用途 |
| --- | --- | --- |
| [07.1](../../docs/README.md#unit-07-1) | [app/public/index.php](app/public/index.php) | 框架入口：自动加载、运行、发送 |
| [07.1 / 07.2](../../docs/README.md#unit-07-1) | [app/route/app.php](app/route/app.php) | 先 learning/hello，再 api/health |
| [07.1](../../docs/README.md#unit-07-1) | [app/app/controller/Learning.php](app/app/controller/Learning.php) | 最小固定响应，不访问数据库 |
| [07.2](../../docs/README.md#unit-07-2) | [app/config/database.php](app/config/database.php) | 数据库连接配置，按需查询 |
| [08.1–08.3](../../docs/README.md#api-login) | [app/app/controller/Product.php](app/app/controller/Product.php) | 先 index，再 create/update/find；网址扩展暂缓 |
| [08.2](../../docs/README.md#api-create) | [app/app/service/ProductInput.php](app/app/service/ProductInput.php) | 创建与更新的输入规则 |
| [08.2](../../docs/README.md#api-create) | [app/app/ExceptionHandle.php](app/app/ExceptionHandle.php) | 只读 render：输入错误怎样变成 422 |
| [08.4](../../docs/README.md#checkpoint-auth) | [app/app/middleware/RequireLogin.php](app/app/middleware/RequireLogin.php) | 先检查登录，再限制写入 |
| [08.4](../../docs/README.md#checkpoint-auth) | [app/app/service/LearningAuth.php](app/app/service/LearningAuth.php) | 读取令牌、用户和角色 |
| [08.1 / 08.4](../../docs/README.md#checkpoint-auth) | [app/app/controller/Auth.php](app/app/controller/Auth.php) | 先使用登录返回，后来再追踪登录/退出 |

参考入口在 examples/；已有 exercises/ 和旧路径下的实验均保留。自己的 my-* 文件按手册创建或继续编辑，不覆盖已有作业。src/public/app 中的文件按职责命名，通过表中入口使用。
