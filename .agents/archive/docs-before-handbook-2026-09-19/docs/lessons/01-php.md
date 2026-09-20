# 第 1–7 天：从 JavaScript 经验迁移到 PHP

目标：用小文件理解输入、判断和输出，第 7 天能自己实现一个验证函数，而不只是运行示例。先做 [环境阶段](../../stages/00-environment/README.md)，命令均在仓库根目录的 PowerShell 执行。不要一次读完七天；每天按“预测 → 运行 → 改一个地方 → 解释”练习。学习深度按 [五个核心问题](../core-knowledge.md) 判断，命令和函数名可以查。

阅读规则：先看示例旁的语句注释，再运行。首次出现的语法应能就地找到解释；重复概念查 [PHP 代码阅读速查](../php-reading-guide.md)，不能用“输出对了”代替读懂代码。

<a id="day-1"></a>

## 第 1 天：环境不等于程序

镜像像“装好工具的模板”，容器像打开的工作间。本项目 php 容器常驻的是 sleep，不是正在计算你的 PHP 代码。exec 才让它另起一次 PHP 进程；脚本执行完该进程就结束，容器还在。与在 Node 容器执行 node app.js 类似，不需要手工编译。

```powershell
# 后台启动工具环境；-d = --detach
docker compose up -d php
# 前一个 php 是服务名，后一个是可执行程序；exec = execute
docker compose exec php php --version
# 把文件交给 PHP 执行，输出会出现在当前终端
docker compose exec php php stages/00-environment/hello.php
```

预期：显示 PHP 8.4 和 hello 文件里的问候。练习：只修改问候名字后重跑，不构建镜像。提示：源码通过挂载共享。验收：能解释为什么两个 php 不能合并。失败先用 docker compose ps 检查状态，不看空的 php 服务日志寻找脚本输出。

运行前看 [hello 语句拆解](../../stages/00-environment/README.md)：`$` 标记变量，`=` 赋值，`;` 结束语句，双引号中的 `{$student}` 会插入值，`\n` 换行。`PHP_VERSION` 是内置版本常量，不是命令。

<a id="day-2"></a>

## 第 2 天：变量、类型、字符串

PHP 的变量名带 $，字符串拼接用点号；JS 常用加号。declare(strict_types=1) 约束当前文件发起的标量参数调用，不会自动验证 HTTP 输入，也不会给普通变量永久指定类型。

路径提醒：这条命令的文件由 Linux 容器中的 PHP 打开，因此路径使用正斜杠 `/`。Windows PowerShell 按 Tab 可能补成 `.\stages\...`，执行前把所有反斜杠 `\` 改为 `/`；`./stages/...` 可以保留，前面的点不是问题。详见 [Docker 轻量伴学的路径说明](../docker-light-guide.md)。

```powershell
# 运行变量示例；打开同名源码，先预测两行结果
docker compose exec php php stages/01-php-syntax/exercises/day02.php
```

预期：两行分别是 Notebook: 9900 和 bool(true)。读 [源码](../../stages/01-php-syntax/exercises/day02.php)。其中 `echo` 只输出内容，不自动换行；`PHP_EOL`（最后是字母 L，不是 F）是 PHP 自带的字符串常量，EOL = End Of Line（行结束），不带 `$`，也不是函数调用。把它拼到文本末尾，就会在终端换行。本项目 PHP 运行在 Linux 容器中，它的值是 `"\n"`；如果 PHP 直接运行在 Windows 中，则是 `"\r\n"`。依据是 PHP 的运行环境，不是你看到的终端窗口。参见 [PHP 官方常量说明](https://www.php.net/manual/en/reserved.constants.php#constant.php-eol)。

`var_dump` 显示值及其类型；`bool` 是 Boolean（布尔类型），所以 `bool(true)` 表示“布尔类型，值为真”。小观察：先预测临时去掉 `. PHP_EOL` 会怎样，再运行并恢复；两段输出会连成 `Notebook: 9900bool(true)`，不是 PHP 程序提前结束。EOF = End Of File（文件结束），与这里的 EOL 不是同一个概念。

练习：添加数量变量，输出两个本子的价格；不要把价格改成浮点元。提示：用整数相乘。验收：输出 19800，解释“分”和“元”的区别，以及第一行为什么会换行。

<a id="day-3"></a>

## 第 3 天：条件和循环

foreach ($prices as $price) 类似 JS 的 for…of；=== 比较类型和值。不要把 PHP 的所有宽松比较规则当作 JS 的规则背下来，后台输入优先验证后严格比较。

本日新写法：`$total += $price` 等于 `$total = $total + $price`；`条件 ? A : B` 在条件成立时取 A，否则取 B；`>=` 是大于或等于。`[]` 建立列表，`{}` 围住每轮循环的代码，双引号中的变量会插入当前值。

```powershell
# 运行循环、小计与运费的参考实现
docker compose exec php php stages/01-php-syntax/exercises/day03.php
```

预期：小计 14900、运费 2000，两个比较依次 false、true。读 [源码](../../stages/01-php-syntax/exercises/day03.php)。练习：将价格列表改成 [29900]，再改成 [29899]。提示：门槛用 >=。验收：免运费边界分别是 0、2000。

<a id="day-4"></a>

## 第 4 天：数组不完全等于 JS 对象

PHP array 同时支持数字索引和字符串键；访问商品字段用 $product['sku']。array_filter 保留索引，转成 JSON 列表前经常要 array_values 重排索引。

本日新写法：`'sku' => 'BOOK'` 是键值关联；`fn (array $p): bool => ...` 则是接收一项商品并返回布尔值的短函数，`=>` 后的结果自动返回。`array_filter` 用它判断保留哪些项；外层 `json_encode` 将数组转成文本，`JSON_THROW_ON_ERROR` 指定失败时抛异常。按“筛选 → 重排键 → 编码 → 输出”读这一行。

```powershell
# 过滤价格至少 5000 分的商品并编码为 JSON
docker compose exec php php stages/01-php-syntax/exercises/day04.php
```

预期：一个包含 BOOK 的 JSON 数组。读 [源码](../../stages/01-php-syntax/exercises/day04.php)。练习：改成只保留 CABLE，分别保留、去掉 array_values。提示：观察输出是方括号列表还是花括号对象。验收：解释为什么前端期望数组时，后端不能返回有空洞的索引。

<a id="day-5"></a>

## 第 5 天：函数与异常

参数 int 和返回值 : int 都由 PHP 运行时检查，区别于会被编译器擦除的 TypeScript 类型。throw 中断当前正常流程，catch 决定怎样报告。

`function shipping(...)` 是我们定义的函数；`return` 结束本次调用并返回运费。`new InvalidArgumentException(...)` 创建输入错误对象，`try/catch` 尝试并接住它；`$error->getMessage()` 调对象方法取错误文字，不会自己输出。源码已在这些语句前逐项解释。

```powershell
# 验证运费函数的门槛和负数错误
docker compose exec php php stages/01-php-syntax/exercises/day05.php
```

预期：2000、0、subtotal must not be negative。读 [源码](../../stages/01-php-syntax/exercises/day05.php)。练习：新增“零小计免运费”规则，但不允许负数。提示：先校验再判断规则。验收：能用 0、-1、29899、29900 四个输入说明行为。

<a id="day-6"></a>

## 第 6 天：读文件与 JSON 错误

本例的 JSON 是商品列表，json_decode(..., true) 将其解成 PHP 数组；其中 true 还指定 JSON 对象解为关联数组，但不代表所有 JSON 顶层值都必然是数组。JSON_THROW_ON_ERROR 让格式错误进入异常处理。__DIR__ 指当前文件目录，不是运行命令时所在目录。

本日参数顺序：`json_decode(文本, true, 512, 选项)` 中 512 是最大嵌套深度，不是行数。`file_get_contents` 返回文件文本或 false，`count` 数数组元素；`Throwable` 接住错误/异常，`fwrite(STDERR, ...)` 写标准错误输出，`exit(1)` 用非零退出码结束脚本。这些边界都已在源码旁解释。

```powershell
# 读取与示例放在一起的 products.json
docker compose exec php php stages/01-php-syntax/exercises/day06.php
```

预期：Products: 2。读 [源码](../../stages/01-php-syntax/exercises/day06.php) 与 [数据](../../stages/01-php-syntax/exercises/products.json)。练习：临时删掉 JSON 的一个逗号，运行后撤销这一个编辑。提示：错误在当前终端，返回码非零。验收：能区分“文件读不到”和“文件不是合法 JSON”。

<a id="day-7"></a>

## 第 7 天：第一次独立交付

不是做订单系统。先阅读 [金额参考](../../stages/01-php-syntax/exercises/order_total.php)，解释金额由哪些步骤得出；独立练习只聚焦“输入是否合法”，不要求这一天重写整套金额计算。

阅读提示：`||` 表示任一条件成立，`intdiv` 做截去小数的整数除法；`sprintf('%s %.2f', ...)` 返回格式化文字，不直接打印。`@param list<array{...}>` 是给读者/工具看的结构注释，不会替你验证每个字段。

```powershell
# 运行参考金额程序，核对自己的计算
docker compose exec php php stages/01-php-syntax/exercises/order_total.php
```

预期：小计 149.00、折扣 14.90、运费 20.00、总额 154.10；免运费门槛使用“折后金额”。独立练习：完成 [输入关卡](../practice-checkpoints.md#checkpoint-input) 的第 7 天部分，只先处理整数、负数、缺失字段。提示：先写规则和预期，再编码。验收：能解释、自己实现并用边界验证；其余类型到第 14 天补齐，不以参考金额输出正确代替独立完成。下一步：[HTTP 与数据库](02-foundations.md)。
