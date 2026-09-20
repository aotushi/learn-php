# PHP 后台开发练习

用 PHP 8.4、MySQL、ThinkPHP 和 Vue，逐步完成能保存商品、验证输入、区分读写权限的小后台。

**唯一学习入口：[PHP 后台开发学习手册](docs/README.md)。**

手册按“环境 → 语法 → 对象与加载 → HTTP → 数据库 → 测试 → 框架接口 → Vue 联调 → 独立交付”推进。学习单元沿用源码目录号，例如 02.3 对应对象与 Composer；每节都列出参考文件、运行入口、自己的练习和预期结果。目录 README 只做反向索引。

examples/ 保存稳定参考；exercises/ 保留你的练习和旧实验。入口和类文件按职责命名，自己的 my-* 文件不参加参考编号。Magento 是第二阶段。

[首次准备](docs/README.md#environment)完成后，日常使用 `docker compose up -d --wait` 启动主线，`docker compose stop` 停止；Magento 使用独立的 compose.magento.yaml。

本项目用于本机学习，默认密码与开发服务器不用于公开部署。
