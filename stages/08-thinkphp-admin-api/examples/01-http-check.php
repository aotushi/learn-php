<?php
// 学习单元 08.5：从项目根打开 docs/README.md#unit-08-5。
declare(strict_types=1);
// 08.5：这个示范只读，不创建账号、商品或令牌。
require dirname(__DIR__, 3) . '/scripts/support/http.php';

$response = learningRequest('/api/learning/hello');
// -> 用于对象，[] 用于数组；这个工具返回 status/body 两个键。
learningCheck($response['status'] === 200, 'HTTP 200');
learningCheck(($response['body']['data']['message'] ?? null) === 'Hello from PHP', 'Response message');
