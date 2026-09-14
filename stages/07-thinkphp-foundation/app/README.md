# ThinkPHP 后台 API 应用

这是第一阶段使用的 ThinkPHP 8.1 应用。请从上一级 [阶段说明](../README.md) 开始，不要直接把框架生成目录当作完整教程。

依赖由根目录安装脚本恢复：

```powershell
# 回到 learn-php 根目录后安装依赖并启动服务
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1
```

主要学习文件：

- `route/app.php`：健康检查和商品路由。
- `app/controller/Product.php`：商品与商品网址接口。
- `config/database.php`：MySQL 环境变量配置。
- `composer.json` 与 `composer.lock`：ThinkPHP 版本和依赖锁定。

本应用基于 [ThinkPHP 8 官方项目模板](https://github.com/top-think/think/tree/8.x)，遵循 Apache-2.0 许可。
