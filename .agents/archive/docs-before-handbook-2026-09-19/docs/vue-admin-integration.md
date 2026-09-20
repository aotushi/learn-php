# 已有 Vue 后台适配：不能只改 API 地址

目标仍是复用已有后台的布局、表格和表单。本仓库的 stages/06-linux-frontend 是最小联调对照工具，不是新建完整后台。它已连接真实 API；已有 resume 后台的完整适配是独立验收项，本次不改那个项目。

## 已核对的边界

当前工作区 resume/admin-backend/frontend 使用以下接口和机制：

| 位置 | 已有行为 | 本教程接入要求 |
| --- | --- | --- |
| src/utils/http/index.ts | VITE_API_BASE_URL；令牌到期时刷新 | 使用同源开发代理；学习模式到期退出并要求重登 |
| src/api/user.ts | /api/auth/login、/api/auth/profile、/refresh-token | 前两项有实现；没有刷新接口，不得照搬自动刷新 |
| src/store/modules/user.ts | 提取 data.user、accessToken、expires 和角色 | 对齐响应字段；退出应撤销服务器令牌 |
| src/router/index.ts | 未登录跳 /login，并检查角色/层级 | 保留守卫，只开放已适配商品页 |
| src/api/products.ts | 商品和网址请求 | 按下表核对；用户关联、上传、报表不在范围内 |

改 API 地址无法补出缺失的登录、用户管理、动态菜单或刷新令牌接口。不要为了复用整套页面而把这些功能全塞回第一阶段。

## 小契约

| 方法 | 路径 | 权限/结果 |
| --- | --- | --- |
| GET | /api/health | 无登录，检查应用及数据库 |
| POST | /api/auth/login | username/password；返回 user、accessToken、expires |
| GET | /api/auth/profile | Bearer 令牌；返回 data.user |
| POST | /api/auth/logout | 撤销当前令牌 |
| GET | /api/products/list | 已登录；data 是商品数组，含 url_count |
| POST | /api/products/create | admin；201，data 是新商品 |
| PUT | /api/products/{id} | admin；本教程允许部分字段更新 |
| DELETE | /api/products/{id} | admin；同时删除其网址 |
| GET / POST | /api/products/{id}/urls | GET 已登录；POST 仅 admin |
| DELETE | /api/products/{productId}/urls/{urlId} | admin，校验网址归属 |

success 为布尔值。失败返回 error 字符串及相应 HTTP 状态：401 未登录/过期、403 无权限、404 不存在、409 SKU 重复、422 输入无效、500 内部错误。金额 price_minor 以分计；支持 CNY/USD/EUR；status 为 enabled/disabled。库存目前只是非负字段，不是并发库存系统。

## 推荐适配顺序

1. 在现有前端的学习分支上处理，不改变生产环境配置。
2. 清空学习环境的 VITE_API_BASE_URL，让 /api 走开发代理；Vite 的 server.proxy 将 /api 指向 http://127.0.0.1:18081。如果前端也在容器内，应使用可达的服务地址，而不是该容器自己的 localhost。
3. 先登录并只开放商品页，保留未经适配页面的禁用/隐藏状态。
4. 学习模式没有 refreshToken：在发请求前发现 expires 过期时清除用户状态并跳登录；响应 401 也这样处理。不要调用 /refresh-token 或把请求挂起在刷新队列中。403 只显示权限错误，不把它当作过期。
5. 退出时 POST /api/auth/logout，随后清除前端登录状态；现有 logOut 只清理前端，必须明确这个差异。
6. 按本页契约调整商品类型与请求；在浏览器实际完成登录、新增、修改、删除，再标记“已有后台已适配”。

这里提供可执行对照：[api.js](../stages/06-linux-frontend/src/api.js) 和 [useAdmin.js](../stages/06-linux-frontend/src/composables/useAdmin.js)。不要整份复制进已有 Axios 层；只迁移所需处理规则。

本教程使用同源代理，因此后端没有开放任意 CORS（Cross-Origin Resource Sharing，跨源资源共享）。直接把基础地址改成另一个端口，会重新引入跨域问题。Vite 代理只服务开发；生产代理、HTTPS（HTTP over TLS）、限流、审计、安全 Cookie 或其他认证方案需要单独设计。

## 认证取舍与验收

随机 Bearer 令牌一小时有效，服务器存摘要，可撤销；不使用 JWT、不刷新、不支持用户自助注册。admin/viewer 只为本机练习。对照示例仅内存保存令牌，整页刷新需重登，因此不需要实现旧后台的跨标签同步。

验收分别记录：

- 小示例：浏览器真实完成商品闭环，viewer 写入被服务器拒绝。
- 已有后台：登录守卫、过期、退出、商品四项真实走通；未支持的菜单明确标识。
- 数据：刷新/重启后自己的商品仍在，非法输入不写入数据库。

仅第一项通过不能替代第二项。
