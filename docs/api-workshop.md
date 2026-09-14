# 接口手动练习：登录 → 创建 → 修改 → 删除

目的：不用 Vue 也能独立检查后端。先运行根 README 的 setup-thinkphp.ps1。以下命令在同一个 PowerShell 终端按顺序执行；新开终端后变量会消失，需要重新登录。默认端口 18081，修改过 .env 的端口则同时修改 $Api。

## 1. 登录并保存请求头

```powershell
# 定义 API 地址；只影响当前 PowerShell 会话
$Api = 'http://localhost:18081'
# 提交本机学习账号；-Method Post 表示 POST 请求，Body 是 JSON 请求体
$Login = Invoke-RestMethod -Method Post -Uri "$Api/api/auth/login" -ContentType 'application/json' -Body '{"username":"admin","password":"LearnPhp123!"}'
# 保存请求头，不把令牌截图、提交 Git 或转发给别人
$Headers = @{ Authorization = "Bearer $($Login.data.accessToken)" }
# 带请求头查询商品列表；Headers 表示 HTTP 请求头
Invoke-RestMethod -Uri "$Api/api/products/list" -Headers $Headers
```

预期：success True 和商品数据。Bearer 表示持有此令牌即可作为该用户访问；它不是 PHP 进程，也不是数据库密码。一小时过期，过期后从本节重登。密码摘要不可还原，令牌本身却可用于访问，因此不要分享。

## 2. 创建并读回

```powershell
# 创建自己的练习商品；未传 SKU 时服务器生成，不覆盖现有商品
$Created = Invoke-RestMethod -Method Post -Uri "$Api/api/products/create" -Headers $Headers -ContentType 'application/json' -Body '{"name":"我的联调商品","price_minor":1234}'
# 保存新 id，后面只修改/删除这个 id
$ProductId = $Created.data.id
# 显示新商品，确认价格是 1234 分
$Created.data
```

预期：生成的 id、SKU、名称和价格。浏览器 Network 可看到 HTTP 201（Created）。练习：记录生成的 SKU，在新请求体中重复使用它，应该得到 409，不会覆盖旧行。

## 3. 修改与验证错误

```powershell
# 只修改本次创建的商品，将价格改为 4567 分
Invoke-RestMethod -Method Put -Uri "$Api/api/products/$ProductId" -Headers $Headers -ContentType 'application/json' -Body '{"price_minor":4567}'
# 故意提交负价格；预计返回 422，PowerShell 可能以红色显示异常
Invoke-RestMethod -Method Put -Uri "$Api/api/products/$ProductId" -Headers $Headers -ContentType 'application/json' -Body '{"price_minor":-1}'
```

预期：成功修改为 4567；错误请求不改变已存价格。练习：将第二个请求改成数组名称，仍应 422。提示：红色错误不一定意味着 Docker 坏了，先看 HTTP 状态和返回信息。

## 4. 删除与退出

以下删除仅影响上面新建的商品及其网址。不要把 $ProductId 换成已有学习数据的 id。

```powershell
# 删除本次新建的商品；删除后不能靠刷新恢复
Invoke-RestMethod -Method Delete -Uri "$Api/api/products/$ProductId" -Headers $Headers
# 撤销当前登录令牌
Invoke-RestMethod -Method Post -Uri "$Api/api/auth/logout" -Headers $Headers
```

预期：success True。练习：退出后再请求列表，应返回 401；然后用 viewer 重登，尝试创建商品应得到 403。验收：不用页面也能说明 201、401、403、409、422 的含义。

## 自动对照

```powershell
# 执行同类请求并断言结果；临时 SMOKE 商品和令牌在结束时清理
docker compose exec php php scripts/api-smoke.php
```

预期末行 API checks passed。该脚本使用本机初始演示账号；如果你已主动更换密码，需要同步测试配置后再执行，不能用重置数据库解决。
