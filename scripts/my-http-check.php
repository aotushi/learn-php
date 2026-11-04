<?php
declare(strict_types=1);


// 加载的两种方式

// method 1
require dirname(__DIR__, 1) . '/scripts/support/http.php';

// method 2
// setUpBeforeClass() is a PHPUnit lifecycle method

// function setUpBeforeClass() {
//   require_once __DIR__ . '/scripts / support / http . php';
// }


$response = learningRequest('/api/learning/hello');

learningCheck($response['status'] === 200, 'HTTP 201');

learningCheck(($response['body']['data']['message'] ?? null) === 'Hello from PHP', 'Response message');