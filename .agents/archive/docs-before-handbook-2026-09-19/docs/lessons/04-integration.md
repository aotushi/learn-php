# 第 19–24 天：Vue 后台联调

目标：登录后从页面读写真实商品，能解释失败。优先复用已有后台；本仓库的小示例是可运行的对照工具，不重新建设后台布局、菜单和页面体系。示例保留 JavaScript，避免在学 PHP 的同时额外引入 TypeScript 工具链。

已有前端经验时，熟悉的组件/构建部分验证后即可压缩，把时间用于 [请求、权限与排错关卡](../practice-checkpoints.md)。本周重点是 PHP 接到什么、数据库改了什么、服务端如何拒绝不该发生的操作，不是重新学一遍 Vue。

<a id="day-19"></a>

## 第 19 天：先让浏览器看到数据

组件地图：App 只组合页面；AdminDemo 连接状态和操作；LoginForm 发登录事件；ProductForm 发保存事件；ProductTable 发编辑/删除事件；useAdmin 管请求状态；api.js 管 HTTP。数据向下传，事件向上传。

```powershell
# 同时准备后端和小型 Vue 示例；首次会下载锁定的 npm 依赖
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1 -WithFrontend
```

打开 http://localhost:15173，用 admin / LearnPhp123! 登录。预期：看到 MySQL 商品。练习：在 Network 找 login 和 list 请求，说明令牌怎样从响应进入 Authorization 请求头。提示：Bearer 意为“持有者”，不是缩写。验收：知道先启动后端、再连接页面，而不是只执行 build。

<a id="day-20"></a>

## 第 20 天：页面修改必须落库

在页面新增一个带自己名字的商品，价格填 1234 分，再改成 4567 分。点击“刷新列表”，价格仍为 45.67。读 [请求层](../../stages/06-linux-frontend/src/api.js) 和 [状态层](../../stages/06-linux-frontend/src/composables/useAdmin.js)。

```powershell
# 构建编译后的页面；build 成功并不等于浏览器流程正确
docker compose --profile frontend run --rm node npm run build
```

预期：构建成功且页面可以实际操作。练习：创建重复 SKU，看页面错误；不要修改别人或种子数据。提示：前端检查 response.ok 和 success 两层。验收：知道失败时不能先把表格当作保存成功。

<a id="day-21"></a>

## 第 21 天：最小登录，不做复杂认证平台

读 [Auth](../../stages/07-thinkphp-foundation/app/app/controller/Auth.php)：password_verify 校验密码摘要，random_bytes 生成随机令牌，数据库只保存令牌摘要及一小时到期时间。不是 JWT（JSON Web Token）；也没有刷新令牌。

源码已说明 `bin2hex` 把随机字节转成十六进制文本，`time()` 以秒计时，`hash` 算摘要，`unset` 只移除响应数组里的敏感字段；[令牌检查](../../stages/07-thinkphp-foundation/app/app/service/LearningAuth.php) 解释了正则提取与过期判断。名字和符号回查 [PHP 阅读速查](../php-reading-guide.md)，不必背密码学实现。

```powershell
# 运行包含错误密码、退出、过期检查的真实接口测试
docker compose exec php php scripts/api-smoke.php
```

预期：未登录 401、合法登录 200，退出后旧令牌 401。练习：按 [权限关卡](../practice-checkpoints.md#checkpoint-auth) 验证服务端退出后旧令牌失效，再观察整页刷新。提示：刷新会丢失示例前端的内存令牌，但不等于服务端撤销令牌；数据库商品也不会丢失。验收：区分“用户密码摘要”和“随机令牌摘要”，说明客户端忘记令牌与服务端拒绝令牌的差别。参考 [PHP 密码文档](https://www.php.net/manual/en/function.password-hash.php)。本机默认账户不适合公开部署。

<a id="day-22"></a>

## 第 22 天：固定权限比完整 RBAC 更适合现在

RBAC = Role-Based Access Control（基于角色的访问控制）。本月只固定 admin 可读写、viewer 只读，不做角色编辑器、动态菜单或多租户。

退出 admin，使用 viewer 和同一初始密码登录。预期：能看表格，看不到写按钮。独立练习：完成 [权限关卡](../practice-checkpoints.md#checkpoint-auth) 的三种身份/两类操作表，用直接请求绕过页面，并读回自己的商品确认拒绝写入后数据不变。提示：服务器仍须返回 403；页面隐藏只用于体验。验收：用证据说明权限在 [RequireLogin](../../stages/07-thinkphp-foundation/app/app/middleware/RequireLogin.php) 执行。

<a id="day-23"></a>

## 第 23 天：接入已有 Vue 后台，不只改地址

按 [已有后台适配](../vue-admin-integration.md) 核对商品、登录、路由守卫、过期处理四处。先在学习分支上只接入商品页；本仓库不修改 resume 项目，也不宣称它现在全站兼容。

预期：写出一张“已有请求 → 本接口/暂不支持”的表。练习：找 /refresh-token 和用户管理请求，说明为什么改 API 地址还不足够。提示：本月到期重新登录，不照搬旧刷新流程。验收：只有四个边界都核对过，才将已有商品页联调标为完成；不能用小示例通过冒充已有后台通过。

<a id="day-24"></a>

## 第 24 天：故障观察

在浏览器开发者工具 Network 中切换 Offline 后点击刷新列表，观察错误，再恢复 Online 重试。这个练习不停止其他项目服务。

```powershell
# 查看本项目服务状态；frontend 负责页面，thinkphp 负责 API
docker compose ps
# 查看 API 服务最近的请求记录
docker compose logs --tail 30 thinkphp
```

预期：页面显示错误，恢复网络后重新请求成功。练习：回答“浏览器根本没发出请求”和“PHP 已收到请求但数据库报错”分别应查哪里，再按 [排错关卡](../practice-checkpoints.md#checkpoint-debug) 在自己写的价格函数中制造、定位并恢复一个边界错误。提示：CLI 输出看当前终端；API 异常再看应用 runtime/log。验收：保留输入、预期、实际、定位证据、修复后结果五行记录，不以“重装后好了”代替根因。下一步：[验收与复盘](05-delivery.md)。
