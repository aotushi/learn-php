# PHP 代码阅读速查：遇到一句，读懂一句

先读当前示例旁的中文注释；本页用于回查，不是额外的必背课程。每段代码都应能回答：**输入是什么、做了什么、返回或输出什么、失败会怎样**。只知道“这段能跑”还不够。

## 按进度找已注释的源码

| 进度 | 阅读入口 | 先读懂什么 |
| --- | --- | --- |
| 第 1 天 | [hello 拆解](../stages/00-environment/README.md) | 标记、变量、赋值、常量、插值、换行 |
| 第 2–3 天 | [day02](../stages/01-php-syntax/exercises/day02.php)、[day03](../stages/01-php-syntax/exercises/day03.php) | `declare`、类型、输出、循环、累加、三元表达式 |
| 第 4–6 天 | [day04](../stages/01-php-syntax/exercises/day04.php)、[day05](../stages/01-php-syntax/exercises/day05.php)、[day06](../stages/01-php-syntax/exercises/day06.php) | 数组、箭头函数、JSON 参数、函数、异常、文件 |
| 第 7 天 | [金额参考](../stages/01-php-syntax/exercises/order_total.php) | 整数除法、格式化、注释不等于验证 |
| 第 8–12 天 | [网页](../stages/03-web-security/public/index.php)、[对象](../stages/02-oop-composer/exercises/day09.php)、[PDO](../stages/04-data-storage/exercises/pdo_products.php)、[JSON 接口](../stages/03-web-security/public/products.php) | 输入/转义、对象、连接、绑定参数 |
| 第 10、18、25 天 | [单元测试](../tests/OrderTotalCalculatorTest.php)、[输入测试](../tests/ProductInputTest.php)、[接口检查](../scripts/api-smoke.php) | 断言、命名参数、请求辅助函数、失败与清理 |
| 第 13–18 天 | [入口](../stages/07-thinkphp-foundation/app/public/index.php)、[路由](../stages/07-thinkphp-foundation/app/route/app.php)、[控制器](../stages/07-thinkphp-foundation/app/app/controller/Product.php)、[验证](../stages/07-thinkphp-foundation/app/app/service/ProductInput.php) | PHP 与框架的边界，链式查询，输入检查 |
| 第 21–22 天 | [登录](../stages/07-thinkphp-foundation/app/app/controller/Auth.php)、[令牌](../stages/07-thinkphp-foundation/app/app/service/LearningAuth.php)、[权限](../stages/07-thinkphp-foundation/app/app/middleware/RequireLogin.php) | 摘要、时间、正则、条件短路、下一层处理 |
| 第 26 天 | [查询计划](../stages/04-data-storage/exercises/explain.php)、[事务](../stages/04-data-storage/exercises/transaction.php) | 结果行、影响行数、回滚与核对 |
| 第二阶段 | [模块注册](../stages/11-magento-module/Acme/Learning/registration.php)、[问候](../stages/11-magento-module/Acme/Learning/Model/Greeting.php)、[命令](../stages/11-magento-module/Acme/Learning/Console/Command/GreetingCommand.php) | 类常量、继承、依赖注入、命令生命周期；不必现在阅读 |

## 1. 基础符号和名字

| 写法 | 怎样读 |
| --- | --- |
| `<?php`、`;`、`//` | 进入 PHP；普通语句结束；行注释。纯 PHP 文件通常省略结束标记 |
| `$name = 'Notebook';` | 把字符串赋给变量；名字由我们起，`=` 不是相等比较 |
| `declare(strict_types=1);` | 开启严格标量类型检查，不给所有变量指定永久类型，也不自动验证请求字段 |
| `'文字'` / `"Hi {$name}\n"` | 单引号不插入变量；双引号会，`{}` 划清变量边界，`\n` 换行 |
| `PHP_VERSION` / `PHP_EOL` | PHP 内置的版本字符串 / 平台换行常量，不带 `$` 或调用括号 |
| `echo` / `var_dump(...)` | 输出内容但不自动换行 / 调试时显示类型和值，不用于正式 JSON 响应 |
| `int`、`string`、`bool`、`array`、`null` | 整数、字符串、布尔、数组、空值；Boolean 的类型声明写 `bool`，不能写 `boolean` |
| `.`、`+=`、`*`、`/` | 拼接字符串、累加赋值、乘法、除法；数组 `+=` 另有含义，见下节 |
| `===`、`!==`、`>=`、`==` | 严格相等、严格不等、大于等于、可能转换类型的相等比较 |
| `!`、`&&`、`\|\|` | 非、且、或；且/或会短路，前面已决定结果时不再计算后面 |
| `条件 ? A : B` / `值 ?: B` / `值 ?? B` | 条件成立选 A / 假值时选 B / 仅缺失或 null 时选 B，不能混为一谈 |

`shipping`、`formatMoney`、`$priceMinor` 是示例自取名称，不是 PHP 内置关键字。类型细节可查 [PHP 类型声明](https://www.php.net/manual/en/language.types.declarations.php)。

## 2. 数组、函数与结果

| 写法 | 怎样读 |
| --- | --- |
| `[]` / `['sku' => 'BOOK']` / `$item['sku']` | 空数组 / 键值关联 / 按键读取；PHP 数组既可表示列表，也可表示映射 |
| `$items[] = $item` | 追加元素，不是读取一个空键 |
| `foreach ($items as $key => $value)` | 逐项取键和值；只需值时省略键。`continue` 跳到下一项 |
| `function shipping(int $amount): int` | 声明函数、输入类型和返回类型；调用时写 `shipping(9900)` |
| `return` / `exit` | 结束当前函数并交回结果 / 结束整个脚本；返回不等于打印 |
| `fn (array $p): bool => ...` | 接收数组、返回布尔值的短函数，表达式自动返回；此处 `=>` 不是数组键值分隔符 |
| `array_filter` / `array_map` / `array_values` | 筛选 / 变换每项 / 重排连续数字键，三者不是同一操作 |
| `array_sum` / `count` / `min` | 求和 / 数元素 / 取较小值；返回结果，不直接输出 |
| `array_key_exists` / `isset` | 前者问键在不在；后者还要求值不为 null |
| `in_array($v, $allowed, true)` | 在允许列表中查找，第三个参数要求严格类型匹配 |
| 数组 `+` / `+=` | 按键补充，左边已有键优先，不是追加所有项。[官方说明](https://www.php.net/manual/en/language.operators.array.php) |
| `foreach (... as &$item)` / `unset($item)` | 引用原元素以便修改 / 解除循环变量引用；这里不会删掉数组元素 |

箭头函数按值捕获用到的外层变量；`static fn` 不绑定 `$this`，仍可捕获普通变量。[官方说明](https://www.php.net/manual/en/functions.arrow.php)

## 3. 文件、JSON、异常和格式化

- `__DIR__` 是当前源文件目录，`dirname(目录, 3)` 向上三级；`require` 执行文件并可接收其 return 值，`require_once` 避免重复加载，与 `use` 引入名称不同。
- `file_get_contents` 返回文件字符串，失败为 false，可能还发出警告；空字符串不一定是读取失败。
- `json_encode` 是 PHP 值转 JSON 文本；`json_decode` 反向转换。`json_decode($text, true, 512, JSON_THROW_ON_ERROR)` 分别设置对象转关联数组、最大嵌套深度、错误抛异常；512 不是行数，合法 JSON 顶层也不一定是数组。[参数说明](https://www.php.net/manual/en/function.json-decode.php)
- `throw new ...` 创建并抛异常；`try/catch` 尝试并捕获；`Throwable` 覆盖 Error 和 Exception。`getMessage` 取错误文字，`error_log` 写日志。
- `fwrite(STDERR, $text)` 写标准错误流（standard error）；`exit(1)` 以失败退出码结束脚本，不是 HTTP 状态 1。
- `finally` 是成功或抛异常后都要尝试的收尾，不是事务，也不能保证断电或强制终止后执行。
- `sprintf` 返回格式化文字，`printf` 直接输出，`print_r` 显示数组结构；`%s` 字符串、`%d` 整数、`%.2f` 两位小数。
- `intdiv` 做整数除法，截去小数；`number_format` 生成展示字符串。金额计算和金额展示要分开。

## 4. 对象与测试

| 写法 | 怎样读 |
| --- | --- |
| `class` / `new` / `__construct` | 定义类 / 创建对象 / 创建时调用构造函数 |
| `$this->minor` / `$price->label()` | 当前对象属性 / 调用对象方法；不用 JS 的点号 |
| `public` / `private` / `protected` | 类外也可访问 / 本类内部 / 本类与继承链 |
| 构造参数 `public int $minor` | 同时接收、声明并初始化属性，叫构造器属性提升 |
| `final` / `readonly` | 禁止继承该类 / 限制属性重新赋值；readonly 不代表递归冻结属性中的其他对象 |
| `namespace` / `use` | 给名称分组 / 引入名称；这里的反斜杠不是容器文件路径 |
| `static` / `::` / `::class` | 类级成员 / 访问类成员 / 获得完整类名字符串，不创建对象 |
| `self::` / `parent::` | 当前类 / 父类；测试中的 assertSame 来自继承的 TestCase |
| `extends` / `interface` / `implements` | 继承父类 / 声明方法约定 / 实现约定，复杂模式暂缓 |
| `token: $viewer` / `?array` / `: void` | 按参数名传值 / 数组或 null / 不返回业务结果 |
| `assertSame(预期, 实际)` / `expectException(类名)` | 严格比对类型和值 / 声明后续操作应抛哪种异常 |

## 5. 请求、数据库和框架

`filter_input` 从请求取值，`filter_var` 验证已有值；`FILTER_UNSAFE_RAW` 不做安全转义，`FILTER_VALIDATE_INT` 失败返回 false，要与合法 0 分开。`trim` 去两端空白，`strlen` 计字节，`mb_strlen` 按编码计字符。

`htmlspecialchars` 用于 HTML 输出；`ENT_QUOTES` 包含单双引号，`ENT_SUBSTITUTE` 替换无效编码。位或 `|` 合并这类选项，不是逻辑或 `||`。`header` 设置响应头，`http_response_code` 设置状态；`<?= $value ?>` 是短输出标签。

PDO（PHP Data Objects）访问数据库，DSN = Data Source Name（数据源名称）；`getenv` 读环境变量。`prepare` 准备 SQL → `execute` 绑定值并执行 → `fetchAll` 取所有行或 `fetchColumn` 取第一行某列。`?` 与 `:minimum_price` 是 SQL 占位符，不是 PHP 条件运算；只能绑定值，不能代替表名/列名白名单。

`SELECT/FROM/WHERE/ORDER BY` 选列/选表/限行/排序；`ASC/DESC` 升序/降序；`INSERT/UPDATE/DELETE` 新增/更新/删除。`COUNT(*)` 数匹配行，`rowCount` 在本例中数更新影响行。`beginTransaction/commit/rollBack` 在同一连接开始/提交/回滚；`EXPLAIN` 看计划，不是计时器。

- ThinkPHP 的 `Route`、`Db`、`Request` 是框架类，`json/app/env` 是框架助手，不是 PHP 内置函数。`json` 返回响应对象，`json_encode` 返回文本。
- `Db::name()->where()->select()->toArray()` 是选表 → 条件 → 查询 → 数组；`find` 查一行，`insertGetId` 返回新 id。链式 `->` 仍是普通 PHP 对象语法。
- `'Product/index'` 指控制器/方法，`:id` 是路由参数；`pattern/completeMatch/middleware` 限制参数/完整匹配/添加中间件。具体参数在路由源码旁解释。
- `ProductInput::validate`、`LearningAuth::user`、控制器的 `$this->find` 是仓库自己定义的方法，定义可以直接找到。不要与同名的框架方法混淆。
- `Closure $next` 接收下一层处理函数；`return $next($request)` 才放行，抛异常后不会执行这一行。
- `password_verify` 验证密码摘要，`hash` 算令牌摘要，`random_bytes/bin2hex` 产生可传输随机令牌，不是可逆解密。
- `preg_match` 用正则取令牌，`$matches[1]` 是第一捕获组；返回 1/0/false 分别表示匹配/不匹配/失败。`time` 取 Unix 秒数，`gmdate('c', ...)` 输出 UTC 的 ISO 8601 时间。

## 怎样确认真的读懂

不看注释，说出一行的输入与结果，再做小变式：累加改成普通赋值、门槛换到两侧、JSON 变坏、输入变数组。先预测再运行，只恢复自己改的地方。独立题仍不提供完整答案，见 [独立关卡](practice-checkpoints.md)。

本页覆盖学习路线明确要求阅读的自写 PHP 示例、测试与框架接入代码；官方框架模板、vendor 依赖与安装维护脚本不是逐行阅读任务。不能因为这些可暂缓，就省略当前练习实际出现的新语法。命令另查 [命令词典](command-glossary.md)。**程序正确与解释完整是两项验收，不能互相替代。**
