<?php
declare(strict_types=1);

// 把收到的 GET 参数写入 Web 服务日志。
// 在另一个终端持续查看 Docker 中的日志：
// -f 是 --follow，持续显示新日志
// docker compose logs -f --tail 20 web
error_log(
    'GET parameters: '
    . json_encode($_GET, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);


// 本练习约定：没有 name，或 name 是空字符串/只有空格时，都使用默认名。
// filter_input 从 URL 的查询参数读取 name：普通值返回字符串，缺少时返回 null，
// name[]=Lin 这种数组形式不是本接口接受的字符串，会返回 false。
$name = filter_input(INPUT_GET, 'name', FILTER_UNSAFE_RAW);

// 声明响应正文是 JSON。header 只设置格式说明，不会替我们生成 JSON。
header('Content-Type: application/json; charset=utf-8');

if ($name === false) {
    // 422 表示服务器看懂了这次 HTTP 请求，但其中的输入不符合接口要求。
    // “可读错误”指 error 中直接说明哪个字段错了、需要什么类型。
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'error' => 'name 必须是字符串，不能使用 name[]=Lin 这样的数组形式。',
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

if ($name === null || trim($name) === '') {
    $name = 'PHP 学习者';
} else {
    $name = trim($name);
}

// PHP 数组不能直接 echo；json_encode 把它编码为可以放进响应正文的 JSON 字符串。
echo json_encode([
    'success' => true,
    'data' => [
        'name' => $name,
        'message' => "你好，{$name}！",
    ],
], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
