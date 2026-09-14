# 阶段 02：基础对象与 Composer

主线只用 [第 9–10 天教程](../../docs/lessons/02-foundations.md)：一个金额对象、Composer 自动加载、一组 PHPUnit 断言。src/Pricing 保留可读参考。

必须：能构造对象、解释构造函数验证、找到 composer.json 的自动加载映射，并实际看过一次断言失败。接口/组合可在第 27 天重构时复习。枚举、复杂折扣策略、值对象体系、依赖倒置不作为开始 ThinkPHP 的前置门槛。

```powershell
# 安装锁定 PHP 依赖
docker compose exec php composer install
# 运行参考测试
docker compose exec php composer test
```

预期显示 OK；失败先看测试报告中的预期/实际值。练习：增加一个金额边界断言，先预测再运行。
