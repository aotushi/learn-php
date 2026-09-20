<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\App;

// [ 应用入口文件 ]

// Composer 生成的自动加载入口；require 执行它，使后面能按类名加载 App 等依赖。
require __DIR__ . '/../vendor/autoload.php';

// new 创建 ThinkPHP App；->http 取 HTTP 应用对象。App 来自上面的 use，不是 PHP 内置类。
$http = (new App())->http;

// run 处理请求并返回响应对象；此时还不等于已把正文发送给浏览器。
$response = $http->run();

// send 发送状态、响应头和正文；end 执行框架的请求结束处理。
$response->send();

$http->end($response);
