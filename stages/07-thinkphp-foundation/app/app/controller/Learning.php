<?php
declare(strict_types=1);
namespace app\controller;

use think\response\Json;

// 07.1：先观察一个没有登录、数据库或写入行为的教学请求。
final class Learning
{
    public function hello(): Json
    {
        // json 是框架助手，返回响应对象；框架入口负责发送状态、头和正文。
        return json(['success' => true, 'data' => ['message' => 'Hello from PHP']]);
    }
}
