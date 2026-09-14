<?php
declare(strict_types=1);
namespace app\middleware;

use app\service\LearningAuth;
use think\exception\HttpException;
use think\Request;

final class RequireLogin
{
    public function handle(Request $request, \Closure $next)
    {
        $user = LearningAuth::user($request);
        // 浏览权限与写权限在服务器检查，隐藏按钮不能替代这里的检查。
        if (!in_array($request->method(), ['GET', 'HEAD'], true) && $user['role'] !== 'admin') {
            throw new HttpException(403, '当前账户只能查看商品。');
        }
        return $next($request);
    }
}
