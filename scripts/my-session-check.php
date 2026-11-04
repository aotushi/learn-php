<?php

declare(strict_types=1);

require __DIR__ . '/support/http.php';

$token="";

try {
  $login = learningRequest('/api/auth/login', 'POST', [
    'username' => 'viewer',
    'password' => 'LearnPhp123!',
  ]);

  learningCheck($login['status'] === 200, 'Viewer login');
  $token = $login['body']['data']['accessToken'];
  $list = learningRequest('/api/products/list', 'GET', null, $token);

  learningCheck($list['status'] === 200, 'Viewer list');
} finally {
  // 无论检查成功或抛异常，都尝试清理自己这次取得的令牌。
  if ($token !== '') {
    $logout = learningRequest('/api/auth/logout', 'POST', null, $token);
    learningCheck($logout['status'] === 200, 'Session cleaned');
  }
}