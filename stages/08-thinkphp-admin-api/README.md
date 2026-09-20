# 商品接口练习索引

学习说明、命令、练习和完成记录都在 [唯一手册](../../docs/README.md)。本页只把文件对应到同一个学习单元；点击单元号进入具体步骤。

| 学习单元 | 文件 | 本次用途 |
| --- | --- | --- |
| [08.1](../../docs/README.md#api-login) | [../07-thinkphp-foundation/app/app/controller/Product.php](../07-thinkphp-foundation/app/app/controller/Product.php) | 使用已有列表和商品方法 |
| [08.5](../../docs/README.md#unit-08-5) | [examples/01-http-check.php](examples/01-http-check.php) | 两条只读 HTTP 断言入口 |
| [08.5](../../docs/README.md#unit-08-5) | [../../scripts/support/http.php](../../scripts/support/http.php) | 可独立加载的请求与断言工具，不自动运行测试 |
| [08.6](../../docs/README.md#product-detail) | [../07-thinkphp-foundation/app/route/app.php](../07-thinkphp-foundation/app/route/app.php) | 自己的详情路由加在已保护的商品组 |
| [08.6](../../docs/README.md#product-detail) | [../07-thinkphp-foundation/app/app/controller/Product.php](../07-thinkphp-foundation/app/app/controller/Product.php) | 自己的详情方法加在这个控制器 |
| [08.7](../../docs/README.md#api-cleanup) | [../../scripts/api-smoke.php](../../scripts/api-smoke.php) | 主线回归；不替代自己的详情检查 |

参考入口在 examples/；已有 exercises/ 和旧路径下的实验均保留。自己的 my-* 文件按手册创建或继续编辑，不覆盖已有作业。src/public/app 中的文件按职责命名，通过表中入口使用。

07 与 08 使用同一套 ThinkPHP 应用；无需在 08 再安装一个后台。
