# 阶段 02：面向对象与 Composer

## 学习内容

- 类、只读对象、构造函数、封装与组合。
- 接口、策略、异常、枚举和依赖倒置。
- 命名空间、PSR-4 自动加载、Composer 与包版本。
- 用 PHPUnit 固定业务行为后再重构。

## 示例

`src/Pricing` 将商品、折扣规则和金额计算分开。先阅读测试，再阅读实现：

```powershell
# 按 composer.lock 安装 PHP 依赖；首次运行会创建 vendor 目录
docker compose exec php composer install

# 执行 composer.json 中名为 test 的脚本，即 PHPUnit 自动测试
docker compose exec php composer test
```

这里的 `install` 表示安装依赖，`test` 是本项目定义的脚本名。`PHPUnit` 是 PHP 的单元测试工具。更多字段见 [命令与缩写词典](../../docs/command-glossary.md)。

## 必做练习

1. 实现 `FixedAmountDiscount`，折扣不能超过商品小计。
2. 实现“满额才打折”的策略。
3. 为零商品、多个商品、100% 折扣和非法价格补测试。
4. 组合会员折扣与优惠码，并明确叠加顺序。

## 验收

- 新增一种优惠方式时不需要修改金额计算器。
- 每个业务边界都有测试，测试名能说明行为。
- 能解释接口带来的替换能力，而不只是背定义。
