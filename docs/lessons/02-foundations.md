# 第 8–12 天：把 PHP 连接到浏览器和数据库

目标：第 12 天返回真实数据库中的 JSON 商品。类、Composer 和 SQL 只学当前链路需要的部分；接口策略、枚举和复杂架构延后。

<a id="day-8"></a>

## 第 8 天：请求与响应

浏览器向服务器发送 HTTP（Hypertext Transfer Protocol）请求，PHP 处理后返回文本。它可以是 HTML，也可以是 JSON；不同于浏览器里的 JS 修改现成页面。先看 [页面源码](../../stages/03-web-security/public/index.php)。

```powershell
# 开启原生 PHP 网页服务；profile 是配置组，不是缩写
docker compose --profile web up -d web
```

打开 http://localhost:8080，提交名字并查看地址栏和浏览器 Network。预期：状态码 200，响应含问候 HTML。练习：输入 <b>Hi</b>，预测显示文字还是粗体。提示：htmlspecialchars 在输出时转义；不是在输入时删除。验收：页面不执行输入的 HTML。失败先检查 web 容器的日志。

<a id="day-9"></a>

## 第 9 天：类只解决一个问题

类把数据和相关行为放到一起；构造函数像 JS constructor。PHP 用 -> 访问对象成员。readonly 让初始化后的金额不可被随意改写。本日不要求掌握全部 OOP（Object-Oriented Programming，面向对象编程）。

```powershell
# 构造一个金额对象并输出格式化价格
docker compose exec php php stages/02-oop-composer/exercises/day09.php
```

预期：CNY 99.00。读 [源码](../../stages/02-oop-composer/exercises/day09.php)。练习：把 9900 改为 -1，读懂异常所在行后恢复。提示：构造函数拒绝无效状态。验收：能解释普通数组和金额对象各适合什么场景。

<a id="day-10"></a>

## 第 10 天：Composer、自动加载、测试

Composer 管 PHP 依赖，角色类似 npm。composer.json 描述需求，composer.lock 锁定依赖；vendor 类似 node_modules。命名空间组织类名，PSR-4（PHP Standard Recommendation 4）规定命名空间和目录的自动加载映射。

```powershell
# 按锁文件安装，不主动升级依赖
docker compose exec php composer install
# 运行已提供的 PHPUnit 单元测试
docker compose exec php composer test
```

预期：测试显示 OK。看 [测试](../../tests/OrderTotalCalculatorTest.php) 和 [自动加载配置](../../composer.json)。练习：临时把断言的总额 13410 改成 13411，看到失败再恢复。提示：断言是预期，不是实现。验收：解释为什么“代码没报错”不等于金额正确。

<a id="day-11"></a>

## 第 11 天：MySQL 表、行、约束

数据库保存持久数据；容器里的 PHP 结束后，行仍在。主键标识一行，SKU 唯一约束防止重复编码。SQL（Structured Query Language）不是 PHP，PHP 只是发送它。

```powershell
# 启动 PHP 和 MySQL，--wait 等待数据库健康；此组不启动缓存
docker compose --profile data up -d --wait php mysql
# 补齐学习表和缺少的种子；不会重置已有商品或密码
docker compose exec php php scripts/setup-data.php
# 执行绑定参数的商品查询
docker compose exec php php stages/04-data-storage/exercises/pdo_products.php
```

预期：至少显示两条种子商品，除非你以前已删除/修改过；初始化会补缺少的种子。读 [表结构](../../docker/mysql/init.sql)。练习：找出 name、sku、price_minor 的类型和约束；为什么价格用整数？验收：能分清宿主机 13307 端口和容器间 mysql:3306。连接失败先看 MySQL 健康状态，不删除数据卷。

<a id="day-12"></a>

## 第 12 天：PDO → JSON 闭环

PDO（PHP Data Objects）是 PHP 访问数据库的接口。prepare + execute 把数据和 SQL 结构分离，不用字符串拼接用户输入。读 [只读接口](../../stages/03-web-security/public/products.php)：连接、验证、查询、JSON 各在哪一行？

```powershell
# 启动只读教学网页服务
docker compose --profile web up -d web
# 查询价格至少 5000 分的商品；Invoke-RestMethod 会解析 JSON
Invoke-RestMethod http://localhost:8080/products.php?minimum=5000
```

预期：success 为 True，data 是数据库商品。练习：把 minimum 改为 -1，看到 422；再改为 20000，结果随数据变化。提示：浏览器 Network 能查看状态和原始响应。验收：能从 URL 指出 PHP 文件、参数绑定、products 表、JSON。这个无登录只读端点仅用于本机教学，不能部署成生产管理接口。下一步：[ThinkPHP](03-thinkphp.md)。
