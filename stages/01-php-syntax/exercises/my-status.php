<?php

// 定义 readStatus(array $input): string，只接受 status 字段为 enabled/disabled。缺少、空串、数组、其他值抛 InvalidArgumentException。在根 tests/MyStatusTest.php 沿用 05.2 的方式加载自己的函数并写成功/失败检查。


declare(strict_types=1);





function readStatus(array $input):string {

  // 因为有 array 类型声明，!is_array 永远不会成立，可以删掉
  if (!isset($input['status'])) {
    throw new InvalidArgumentException('缺少 status 字段');
  }

  $status = $input['status'];

  if ($status !== 'enabled' && $status !== 'disabled') {
    throw new InvalidArgumentException('status 只能是 enabled 或 disabled');
  }

  return 'ok';
}