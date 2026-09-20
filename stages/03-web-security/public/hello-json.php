<?php
declare(strict_types=1);
// 03.2：这个文件只响应固定 JSON，先分清数组、编码后的文本、响应头。
header('Content-Type: application/json; charset=utf-8');
$message = ['success' => true, 'data' => ['message' => 'Hello from PHP']];
// header 不会自动编码数组；echo 把编码后的字符串写到响应正文。
echo json_encode($message, JSON_THROW_ON_ERROR);
