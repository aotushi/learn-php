# 第 29 天独立题：让 PHP 筛选商品

目的：把“请求 → 验证 → 查询 → 响应 → 权限 → 测试”连起来，不重写 Vue 页面。本题是学习者待实现的任务；当前参考 API 的列表方法没有这些筛选，附带未知查询参数可能仍返回全部商品，不代表任务已完成。

先完成 [输入](../practice-checkpoints.md#checkpoint-input)、[数据](../practice-checkpoints.md#checkpoint-data) 与 [权限](../practice-checkpoints.md#checkpoint-auth) 关卡。可以查框架参数读取/查询方法，但不复制完整筛选答案。先列出规则和用例，再修改自己的学习分支。

## 小契约

只扩展现有 GET /api/products/list，保留原登录检查、商品字段及 success/data 外形，不新建无权限保护的平行接口。

| 输入 | 要求 |
| --- | --- |
| 不传任何筛选参数 | 行为与旧列表相同，保持兼容 |
| status=enabled 或 disabled | 只返回该状态的商品 |
| 不传 status | 不限制状态 |
| status 为空、是数组或其他值 | 422，不悄悄当作“不筛选” |
| minimum_price=0 或十进制整数字符串 | 允许 0–2147483647，包含等于门槛的价格；前导零允许 |
| 不传 minimum_price | 不限制最低价格 |
| minimum_price 为空、负数、小数、空格、数组或超出范围 | 422 |
| 两个合法参数一起提供 | 两个条件同时成立（AND），不是满足其一 |
| 没有匹配商品 | 200，data 是 [] |
| 没有合法登录令牌 | 401；viewer 允许执行只读筛选 |

数据必须在数据库查询阶段筛选，不先读全表再由 Vue 或 PHP 数组过滤。不要添加分页、模糊搜索、多币种换算等功能；本题测试商品统一用 CNY，minimum_price 直接比较存储的“分”。

## 动手顺序

1. 打开 [Product/index](../../stages/07-thinkphp-foundation/app/app/controller/Product.php)，标出请求读取、验证、查询、输出各应该放在哪里；先不要改代码。
2. 写出查询参数的类型和范围验证，把“缺失”和“空字符串”分开。可以提取一个小验证函数，但不设计通用查询框架。
3. 用框架查询构造器绑定值，按合法条件增加 WHERE。不要把用户输入拼成 SQL。
4. 用现有创建接口准备三件你自己的练习商品：A enabled/5000，B disabled/5000，C enabled/4999；另选一个足够高的门槛并确认当前数据库没有匹配商品。记录它们的 id，不修改种子。
5. 写自己的断言：enabled+5000 必须含 A、排除 B/C；disabled 必须含 B、排除 A/C；0 必须被接受；没有匹配项返回空数组。结果可能还包含其他用户商品，不应断言总数恰好等于 1。
6. 增加三个坏输入、未登录、viewer 只读和无参数兼容检查；完成后仅删除刚创建的 A/B/C，注销测试令牌。

只有“返回 200”不是筛选测试，必须核对具体行的状态和金额。

## 手动观察（实现之后）

在同一个 PowerShell 终端按 [接口练习](../api-workshop.md) 的登录节设置 $Api 和 $Headers；端口按本机配置。以下是只读请求，不会改变商品。

```powershell
# 兼容检查：不传筛选条件，仍得到原来的列表外形
Invoke-RestMethod -Uri "$Api/api/products/list" -Headers $Headers
# 两个条件同时生效；URL 放在引号中，& 才作为地址的一部分传递
Invoke-RestMethod -Uri "$Api/api/products/list?status=enabled&minimum_price=5000" -Headers $Headers
# 故意发一个无效门槛；完成实现后必须是 422，而不是无条件返回全部商品
Invoke-RestMethod -Uri "$Api/api/products/list?minimum_price=-1" -Headers $Headers
```

预期：前两项 success True，第二项每一行均满足两条件；第三项出现 422 和可读错误。若当前仍返回 200 全部商品，先查你是否真正读取/验证参数，不要重装 Docker。

```powershell
# 已有单元测试仍应通过；还要运行你自己新写的筛选用例
docker compose exec php composer test
# 原 API 的创建、修改、删除和权限不能因新筛选而退化
docker compose exec php php scripts/api-smoke.php
```

这两个已有命令只保护参考功能，不会自动证明新筛选实现正确。必须补充上面的行内容、边界和权限检查。

<details>
<summary>卡住再看提示</summary>

不要把查询参数先转成整数再验证。不要用一个默认值把“缺失”和“空值”合并。查询构造器应在 select 之前加入条件；读取结果后再过滤，会掩盖数据库查询并未改变的问题。无参数时不加条件，就能保留原行为。

</details>

## 最终验收与隔天复述

不看答案解释：为什么条件用 AND、为什么验证在查询前、为什么 viewer 能筛选但不能修改、为什么空列表不是异常、为什么旧测试通过仍不够。再在纸上把最低价筛选换成最大库存筛选，列出需要改变的输入规则和测试；不要求当天实现第二个功能。
