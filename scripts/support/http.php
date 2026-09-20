<?php
declare(strict_types=1);

// 08.5：只定义工具函数；require 本文件不会发送请求、登录或运行测试。
// $path 是 /api/...；调用端在 php 容器内，默认通过服务名 thinkphp 访问后端。
// ?array 接受数组或 null，默认值让 GET 可以省略正文与令牌。
function learningRequest(string $path, string $method = 'GET', ?array $body = null, string $token = ''): array
{
    $base = getenv('API_BASE_URL') ?: 'http://thinkphp:8000';
    $curl = curl_init($base . $path);
    try {
        // cURL 是 HTTP 客户端扩展。选项依次为返回正文、请求方法、超时秒数、请求头。
        $headers = ['Accept: application/json', 'Content-Type: application/json'];
        if ($token !== '') { $headers[] = 'Authorization: Bearer ' . $token; }
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        if ($body !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body, JSON_THROW_ON_ERROR));
        }
        $text = curl_exec($curl);
        // 网络失败是 false；HTTP 401/422 仍有响应，留给学习者检查状态。
        if ($text === false) { throw new RuntimeException(curl_error($curl)); }
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $payload = json_decode($text, true, 512, JSON_THROW_ON_ERROR);
        // 成功和失败响应都要求 success 是布尔；不把错误页或无关 JSON 当成接口结果。
        if (!is_array($payload) || !is_bool($payload['success'] ?? null)) {
            throw new UnexpectedValueException('Expected a JSON response with boolean success');
        }
        return ['status' => $status, 'body' => $payload];
    } finally {
        curl_close($curl);
    }
}

// 断言不成立时抛异常；无人捕获就使命令失败，不能只打印一行 FAIL 后继续成功退出。
function learningCheck(bool $condition, string $message): void
{
    if (!$condition) { throw new RuntimeException($message); }
    echo 'PASS: ' . $message . PHP_EOL;
}
