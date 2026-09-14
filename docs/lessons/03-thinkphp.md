# 第 13–18 天：ThinkPHP 商品 API

目标：第 18 天掌握商品增删改查和验证。API 是 Application Programming Interface（应用程序接口）。参考项目已包含简单登录以保护写操作；现在把登录当作现成工具，第 21 天再拆解，不要求提前实现。

每次新开 PowerShell，先按 [接口手动练习](../api-workshop.md) 的“登录”节获得 $Headers；它只存在于当前终端。所有写入只针对自己创建的商品。

<a id="day-13"></a>

## 第 13 天：框架替你做什么

PHP 是语言；ThinkPHP 帮你把 URL 分配给处理函数，并组织配置、数据库访问和错误处理。和 Express/Nest 的路由思想可以类比，但生命周期并不完全一样。

```powershell
# 安装锁定依赖、补齐本地学习表，并等到应用和数据库可用
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1
# 查看应用和数据库共同的健康结果
Invoke-RestMethod http://localhost:18081/api/health
```

预期：success True、框架版本 8.1.x。按 [阶段 07](../../stages/07-thinkphp-foundation/README.md) 顺序读入口、路由、控制器。练习：健康响应新增一个固定 course 字段。提示：先改返回数组，不碰数据库。验收：解释为什么框架版本不等于 PHP 8.4。

<a id="day-14"></a>

## 第 14 天：输入不是可信类型

前端表单验证能改善体验，但别人可以不经过页面直接请求 API。PHP 严格类型也不能替你验证数组里的业务字段。读 [ProductInput](../../stages/07-thinkphp-foundation/app/app/service/ProductInput.php)。

```powershell
# 只运行商品输入规则测试；--filter 按测试名筛选
docker compose exec php composer test -- --filter ProductInput
```

预期：正常字段通过，数组名称、负数价格、未知状态都被拒绝。练习：在测试中加一个超长 description 案例再运行。提示：已有上限 4000。验收：能分清“JSON 类型合法”和“业务允许这个值”。

<a id="day-15"></a>

## 第 15 天：创建并读回商品

CRUD = Create、Read、Update、Delete。读 [Product 控制器](../../stages/07-thinkphp-foundation/app/app/controller/Product.php) 的 create 和 index，再执行 [手动练习](../api-workshop.md) 的登录、创建、查询部分。

```powershell
# 列出路由，核对 POST create 与 GET list 的区别
docker compose exec thinkphp php think route:list
```

预期：创建返回 201 和新 id，列表能查到相同 id。练习：重复提交相同 SKU，预测 409。提示：SKU 唯一约束与应用检查一起工作。验收：价格单位正确，刷新查询仍然存在，不是前端内存假数据。

<a id="day-16"></a>

## 第 16 天：修改和删除也有边界

本教程 PUT 接口接受部分字段更新，这是明确的本地契约；不要据此认定所有 PUT API 都是部分更新。按照 [手动练习](../api-workshop.md) 修改价格，再删除自己创建的商品。

```powershell
# 运行集成检查，观察 create/update/delete 三步及缺失商品的结果
docker compose exec php php scripts/api-smoke.php
```

预期：检查全通过，临时 SMOKE 商品自动清理。练习：在手动练习中删除后再次修改同一 id。提示：资源不存在应返回 404，不能假装成功。验收：能解释删除商品时其网址为何一起删除（外键级联）。

<a id="day-17"></a>

## 第 17 天：统一错误和权限边界

400/422 表示输入问题，401 表示未登录或过期，403 表示已登录但没权限，404 是不存在，500 是服务器异常。这里只把预计的业务错误告诉用户，详细堆栈留在 runtime/log。

```powershell
# 查看 Web 服务器输出；不要换成 logs php
docker compose logs --tail 30 thinkphp
```

预期：有请求访问记录。练习：在浏览器请求 /product/create?name=Bypass，必须 404；它不能绕过注册路由。提示：url_route_must=true 阻止默认控制器访问。验收：说明“按钮隐藏”为什么不能防止越权。

<a id="day-18"></a>

## 第 18 天：读懂一次完整检查

打开 [集成检查](../../scripts/api-smoke.php)，顺着 health → login → create → update → delete 阅读。无需背 cURL 全部选项，先找“发请求、检查状态、清理自己数据”的边界。

```powershell
# 再次运行自动验证，确认重复运行不会留下测试商品
docker compose exec php php scripts/api-smoke.php
```

预期：末行 API checks passed。练习：加一个只读账户更新商品应返回 403 的检查。提示：已有只读创建、删除案例可参照。验收：能说出单元测试和这次真实 HTTP/MySQL 检查分别发现什么问题。下一步：[Vue 联调](04-integration.md)。
