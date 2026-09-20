<?php

// use 引入类名；Route/Db 是 ThinkPHP 提供的门面（便捷入口），不是 PHP 自带类。
use think\facade\Route;
use think\facade\Db;
use app\middleware\RequireLogin;

// 07.1：最小只读教学入口，先跟通路由与响应；商品权限仍由下方路由组保护。
Route::get('api/learning/hello', 'Learning/hello')->completeMatch();

// :: 调用类入口；get 注册 GET 路由。function () {...} 是匿名函数，收到请求时才执行函数体。
Route::get('api/health', function () {
    // SELECT 1 用最小查询验证数据库可用；json 是 ThinkPHP 助手，返回响应对象，不是 json_encode 文本。
    Db::query('SELECT 1');
    return json([
        'success' => true,
        'data' => [
            'service' => 'thinkphp-admin-api',
            // app() 取应用对象，再用 ->version() 取框架版本。
            'framework' => app()->version(),
        ],
    ]);
});

// 'Auth/login' 指控制器 Auth 的 login 方法，不是磁盘上的相对文件路径。
Route::post('api/auth/login', 'Auth/login');
Route::get('api/auth/profile', 'Auth/profile');
Route::post('api/auth/logout', 'Auth/logout');

// group 给内部路由加共同前缀；:id 是路由参数，匹配后的数字会传给控制器。
Route::group('api/products', function () {
    Route::get('list', 'Product/index');
    Route::post('create', 'Product/create');
    Route::get(':id/urls', 'Product/urls');
    Route::post(':id/urls', 'Product/addUrls');
    Route::delete(':productId/urls/:urlId', 'Product/deleteUrl');
    Route::put(':id', 'Product/update');
    Route::delete(':id', 'Product/delete');
// 以下 -> 是链式配置：每一步返回可继续配置的对象；不是嵌套的 PHP 箭头函数。
})
    // 正则 \d+ 表示一个或多个数字；限制参数格式，不等于已验证商品存在。
    ->pattern([
        'id' => '\d+',
        'productId' => '\d+',
        'urlId' => '\d+',
    ])
    // completeMatch 要求整条路径匹配；middleware 给整组加登录/权限检查。
    ->completeMatch()
    ->middleware(RequireLogin::class);
