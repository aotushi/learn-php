<?php
declare(strict_types=1);
namespace app\middleware;

use app\service\LearningAuth;
use think\exception\HttpException;
use think\Request;

final class RequireLogin
{
    // Closure 是可调用的匿名函数对象；框架给 $next 的意义是“交给下一层处理”。
    public function handle(Request $request, \Closure $next)
    {
        $user = LearningAuth::user($request);
        // 浏览权限与写权限在服务器检查，隐藏按钮不能替代这里的检查。
        // method() 取请求方法；!in_array(..., true) 表示严格判断“不在只读方法列表中”。
        if (!in_array($request->method(), ['GET', 'HEAD'], true) && $user['role'] !== 'admin') {
            throw new HttpException(403, '当前账户只能查看商品。');
        }
        // 检查通过才调用下一层；若上面 throw，就不会运行这一行。
        return $next($request);
    }
}
