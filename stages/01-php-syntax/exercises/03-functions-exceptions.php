<?php
declare(strict_types=1);
// function 定义名为 shipping 的函数；括号里是输入，: int 是返回整数的声明。
// int = integer（整数），由 PHP 运行时检查，不同于会被擦除的 TypeScript 类型。
function shipping(int $subtotalMinor): int
{
    // if 条件为真才执行块内语句；< 0 拒绝负数。
    if ($subtotalMinor < 0) {
        // new 创建异常对象；throw 中断正常流程并交给匹配的 catch，不是普通 return。
        throw new InvalidArgumentException('subtotal must not be negative');
    }
    // return 结束这次函数调用并交回值；三元表达式见 02-control-flow.php。
    return $subtotalMinor >= 29900 ? 0 : 2000;
}
// shipping 是我们定义的函数，不是 PHP 内置函数；传入门槛两侧的值，得到 2000 和 0。
echo shipping(29899) . PHP_EOL;
echo shipping(29900) . PHP_EOL;
// try 中执行可能失败的调用；catch 捕获这种异常，并用 $error 保存异常对象。
try { shipping(-1); } catch (InvalidArgumentException $error) {
    // -> 调用对象方法；getMessage() 返回异常文字，本身不负责输出。
    // echo $error->getMessage() . PHP_EOL;
    // get type
    echo get_debug_type($error);
    echo gettype($error);
    var_dump($error);
}
