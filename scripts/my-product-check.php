<?php

declare(strict_types=1);

// 08.7：在项目根目录运行 docker compose exec php php scripts/my-product-check.php。
// 前置：完成 08.6 的 GET /api/products/{id}；尚未实现时，详情检查会失败。
// 这里是发请求的检查脚本；商品接口的实现仍在 ThinkPHP 应用中。
require __DIR__ . '/support/http.php';

/*
 * 正常流程：
 * 1. admin、viewer 分别登录，保存本次返回的两个令牌。
 * 2. admin 新建一件临时商品，保存返回的 id。
 * 3. admin、viewer 能读详情；不登录不能读。
 * 4. viewer 改价格应被拒绝；admin 再读，确认价格没变。
 * 5. admin 删除商品；仍用有效令牌查详情，确认 404。
 * 6. 两个账户分别退出；各自的旧令牌再访问列表，确认 401。
 * finally 是失败时也会尝试执行的收尾：先删剩余商品，再撤销剩余令牌。
 */

// 如果自己改过账户密码，在这里填写当前密码；脚本不会创建或重置账户。
$passwords = ['admin' => 'LearnPhp123!', 'viewer' => 'LearnPhp123!'];
$tokens = ['admin' => '', 'viewer' => ''];
$productId = 0; // 0 表示尚未取得本次新建商品的 id；不要手动填已有商品 id。
$productNeedsCleanup = false;
$failed = false; // catch 记录失败，等 finally 收尾完成后再决定退出码。

// random_bytes 生成随机字节，bin2hex 转成可打印字符，减少重复运行时 SKU 冲突。
$sku = 'MY-CHECK-' . bin2hex(random_bytes(6));
$initialPrice = 4567; // 金额单位是分。
$newProduct = [
  'sku' => $sku,
  'name' => '08.7 临时检查商品',
  'price_minor' => $initialPrice,
  'status' => 'enabled',
];

try {
  // 第 1 步：foreach 的 $role 是数组键，$password 是对应的值。
  // learningRequest(路径, 方法, 正文数组或 null, 令牌) 返回 status/body 数组。
  // null 表示不发 JSON 正文；response['status'] 是 HTTP 状态，不是商品的 enabled/disabled。
  foreach ($passwords as $role => $password) {
    $login = learningRequest('/api/auth/login', 'POST', [
      'username' => $role,
      'password' => $password,
    ]);
    // ?? 在字段缺少或为 null 时提供后备值；先记录令牌，方便后续失败时清理。
    $token = $login['body']['data']['accessToken'] ?? '';
    $tokens[$role] = is_string($token) ? $token : '';
    learningCheck($login['status'] === 200, "$role login: expected HTTP 200, got {$login['status']}");
    learningCheck($login['body']['success'] === true && $tokens[$role] !== '', "$role received a token");
  }

  // 第 2 步：只有 admin 能创建和删除商品；viewer 用来检查只读权限。
  $created = learningRequest('/api/products/create', 'POST', $newProduct, $tokens['admin']);
  // 先保存 id，再检查其余响应。即使后面的断言失败，finally 仍知道该删哪件商品。
  $productId = (int) ($created['body']['data']['id'] ?? 0);
  $productNeedsCleanup = $productId > 0;
  learningCheck($created['status'] === 201, "Create: expected HTTP 201, got {$created['status']}");
  learningCheck($created['body']['success'] === true && $productId > 0, 'Created product has an id');
  echo "Temporary product: id=$productId, sku=$sku" . PHP_EOL;
  $productPath = '/api/products/' . $productId; // . 把路径和 id 拼成字符串。

  // 第 3 步：检查新建商品的详情，不能仅看 200，还要核对读回的是本次商品。
  $detail = learningRequest($productPath, 'GET', null, $tokens['admin']);
  learningCheck(
    $detail['status'] === 200,
    "Admin detail: expected HTTP 200, got {$detail['status']} (requires the 08.6 detail endpoint)",
  );
  learningCheck($detail['body']['success'] === true, 'Admin detail reports success');
  $product = $detail['body']['data'] ?? null;
  learningCheck(is_array($product), 'Detail contains product data');
  // (int) 是对传入参数的值做的类型转换
  learningCheck((int) ($product['id'] ?? 0) === $productId, 'Detail id matches the created product');
  foreach ($newProduct as $field => $expected) {
    // $expected 来自上面自己提交的数据；右边取的是接口实际返回值。
    learningCheck(($product[$field] ?? null) === $expected, "Detail field matches: $field");
  }

  $viewerDetail = learningRequest($productPath, 'GET', null, $tokens['viewer']);
  learningCheck($viewerDetail['status'] === 200, "Viewer detail: expected HTTP 200, got {$viewerDetail['status']}");
  learningCheck(
    $viewerDetail['body']['success'] === true
      && (int) ($viewerDetail['body']['data']['id'] ?? 0) === $productId,
    'Viewer can read the same product',
  );
  $anonymousDetail = learningRequest($productPath); // 不传第 4 个参数，就不携带令牌。
  learningCheck(
    $anonymousDetail['status'] === 401 && $anonymousDetail['body']['success'] === false,
    "Anonymous detail: expected HTTP 401, got {$anonymousDetail['status']}",
  );

  // 第 4 步：故意用 viewer 改成另一个价格。403 表示已登录，但没有写入权限。
  $denied = learningRequest($productPath, 'PUT', ['price_minor' => 8888], $tokens['viewer']);
  learningCheck(
    $denied['status'] === 403 && $denied['body']['success'] === false,
    "Viewer update: expected HTTP 403, got {$denied['status']}",
  );
  // 必须重新发请求读回；只检查刚才保存在 $product 中的旧数组，不能证明数据库没变。
  $after = learningRequest($productPath, 'GET', null, $tokens['admin']);
  learningCheck($after['status'] === 200 && $after['body']['success'] === true, 'Read product after rejected update');
  learningCheck((int) ($after['body']['data']['id'] ?? 0) === $productId, 'Read back the same product');
  foreach ($newProduct as $field => $expected) {
    learningCheck(($after['body']['data'][$field] ?? null) === $expected, "Rejected update left $field unchanged");
  }

  // 第 5 步：删除后先查 404，此时 admin 仍登录，才能验证“商品不存在”。
  $deleted = learningRequest($productPath, 'DELETE', null, $tokens['admin']);
  learningCheck($deleted['status'] === 200 && $deleted['body']['success'] === true, 'Admin deleted the temporary product');
  $missing = learningRequest($productPath, 'GET', null, $tokens['admin']);
  learningCheck(
    $missing['status'] === 404 && $missing['body']['success'] === false,
    "Deleted product detail: expected HTTP 404, got {$missing['status']}",
  );
  $productNeedsCleanup = false; // 确认确实查不到后，finally 才无须再次删除。

  // 第 6 步：先保存旧令牌，再退出，最后用旧令牌访问列表验证失效。
  // 选列表是为了让这一步只检查登录状态，不混入“商品已删除”的问题。
  foreach (['viewer', 'admin'] as $role) {
    $oldToken = $tokens[$role];
    $logout = learningRequest('/api/auth/logout', 'POST', null, $oldToken);
    learningCheck($logout['status'] === 200 && $logout['body']['success'] === true, "$role logout");
    $revoked = learningRequest('/api/products/list', 'GET', null, $oldToken);
    learningCheck(
      $revoked['status'] === 401 && $revoked['body']['success'] === false,
      "$role old token: expected HTTP 401, got {$revoked['status']}",
    );
    $tokens[$role] = ''; // 确认旧令牌失效后，才标记无须清理；清空变量本身不等于注销。
  }
} catch (Throwable $error) {
  // learningCheck 失败会抛异常，立即跳过 try 内剩余步骤来到这里。
  // Throwable 能接住断言、网络等异常以及 PHP Error；getMessage() 给出具体原因。
  $failed = true;
  fwrite(STDERR, 'FAIL: ' . $error->getMessage() . PHP_EOL);
} finally {
  // 兜底收尾：无论成功还是失败都执行，但只处理本次记录且尚未清理的资源。
  // 必须先删商品，再撤销 admin 令牌；否则 DELETE 会因未登录而失败。
  if ($productNeedsCleanup && $tokens['admin'] !== '') {
    try {
      $cleanup = learningRequest('/api/products/' . $productId, 'DELETE', null, $tokens['admin']);
      // 兜底时 404 也表示商品已不存在；正常流程的删除检查仍严格要求 200。
      learningCheck(
        ($cleanup['status'] === 200 && $cleanup['body']['success'] === true)
          || ($cleanup['status'] === 404 && $cleanup['body']['success'] === false),
        "Cleanup product $productId: expected HTTP 200 or 404, got {$cleanup['status']}",
      );
    } catch (Throwable $error) {
      $failed = true;
      // 单独接住清理错误，让下方的两个令牌仍有机会被撤销。
      fwrite(STDERR, "CLEANUP FAILED (id=$productId, sku=$sku): " . $error->getMessage() . PHP_EOL);
    }
  }

  foreach (['viewer', 'admin'] as $role) {
    if ($tokens[$role] === '') {
      continue; // 没登录成功或已经退出，不发送注销请求。
    }
    try {
      $cleanup = learningRequest('/api/auth/logout', 'POST', null, $tokens[$role]);
      // 401 说明这个令牌已经无效；200 说明本次成功撤销。
      learningCheck(
        ($cleanup['status'] === 200 && $cleanup['body']['success'] === true)
          || ($cleanup['status'] === 401 && $cleanup['body']['success'] === false),
        "Cleanup $role session: expected HTTP 200 or 401, got {$cleanup['status']}",
      );
    } catch (Throwable $error) {
      $failed = true;
      fwrite(STDERR, "CLEANUP FAILED ($role session): " . $error->getMessage() . PHP_EOL);
    }
  }
}

// catch 已经接住异常，因此要显式 exit(1)，否则只打印 FAIL 仍可能被当成执行成功。
// exit 放在 finally 之后，让清理先执行。PowerShell 用 $LASTEXITCODE 查看：0 成功，1 失败。
if ($failed) {
  exit(1);
}
echo 'All product checks passed; temporary product and sessions cleaned.' . PHP_EOL;
