<?php
declare(strict_types=1);

// 集成测试只创建随机 SKU 的临时商品，finally 中删除；不删除种子或学习者数据。
$base = getenv('API_BASE_URL') ?: 'http://thinkphp:8000';
$count = 0;
$tokens = [];
$created = [];
// 默认只检查主线；网址扩展由维护者显式加 --with-extensions 后检查。
$withExtensions = in_array('--with-extensions', $argv, true);

// callApi/check/expectStatus 是本文件定义的辅助函数，不是 PHP 内置 API。
// ?array 允许数组或 null；默认值让只读请求省略正文。返回数组约定为 [状态码, 解码后的响应]。
function callApi(string $path, string $method = 'GET', ?array $body = null, string $token = ''): array
{
    // global 显式使用文件外层变量；与箭头函数自动捕获不同。
    global $base;
    // cURL 是 PHP 的 HTTP 客户端扩展：init 创建请求句柄，setopt_array 批量配置选项。
    $curl = curl_init($base . $path);
    // RETURNTRANSFER 返回正文而非直接输出；CUSTOMREQUEST 设置方法；TIMEOUT 的单位为秒；HTTPHEADER 设置头。
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token],
    ]);
    // POSTFIELDS 指定请求正文，不代表强制把所有请求改为 POST；本例已用 CUSTOMREQUEST 设置方法。
    if ($body !== null) { curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body, JSON_THROW_ON_ERROR)); }
    $text = curl_exec($curl);
    if ($text === false) { throw new RuntimeException(curl_error($curl)); }
    // exec 发请求；getinfo 读取 HTTP 状态。失败时 curl_error 给出连接错误，不应伪装成成功 JSON。
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    return [$status, json_decode($text, true, 512, JSON_THROW_ON_ERROR)];
}

function check(bool $condition, string $message): void
{
    global $count;
    if (!$condition) { throw new RuntimeException($message); }
    // ++$count 先加 1 再用于输出；void 表示这个检查函数不返回业务结果。
    echo 'PASS ' . ++$count . ': ' . $message . "\n";
}

function expectStatus(array $response, int $expected, string $message): array
{
    // 数字键 0/1 来自 callApi 的返回约定；is_bool 检查类型，?? 在缺失/null 时提供后备值。
    check($response[0] === $expected, "$message (HTTP $expected; got {$response[0]})");
    check(is_bool($response[1]['success'] ?? null), 'JSON response envelope');
    return $response[1]['data'] ?? [];
}

try {
    expectStatus(callApi('/api/health'), 200, 'Health including database');
    expectStatus(callApi('/api/products/list'), 401, 'No token rejected');
    expectStatus(callApi('/api/auth/login', 'POST', ['username' => 'admin', 'password' => 'wrong']), 401, 'Wrong password');
    foreach (['admin', 'viewer'] as $name) {
        $login = expectStatus(callApi('/api/auth/login', 'POST', ['username' => $name, 'password' => 'LearnPhp123!']), 200, "$name login");
        $tokens[$name] = $login['accessToken'];
    }
    $admin = $tokens['admin'];
    $viewer = $tokens['viewer'];
    // token: 是命名参数，可跳过前面有默认值的参数，不是 JSON 字段声明。
    expectStatus(callApi('/api/auth/profile', token: $viewer), 200, 'Profile');
    expectStatus(callApi('/api/products/list', token: $viewer), 200, 'Viewer can read');
    expectStatus(callApi('/api/products/create', 'POST', ['name' => 'Not created'], $viewer), 403, 'Viewer cannot write');
    expectStatus(callApi('/product/create?name=Bypass'), 404, 'Controller/action bypass blocked');
    expectStatus(callApi('/api/products/create?name=Bypass'), 404, 'GET cannot create');
    $sku = 'SMOKE-' . bin2hex(random_bytes(6));
    $product = expectStatus(callApi('/api/products/create', 'POST', ['name' => 'Smoke test', 'sku' => $sku, 'price_minor' => 1234], $admin), 201, 'Create');
    $id = (int) $product['id'];
    $created[] = $id;
    expectStatus(callApi('/api/products/create', 'POST', ['name' => 'Duplicate', 'sku' => $sku], $admin), 409, 'Duplicate SKU');
    foreach ([['name' => []], ['name' => str_repeat('x', 256)], ['price_minor' => -1],
        ['price_minor' => true], ['currency' => 'BAD'], ['status' => 'other']] as $bad) {
        expectStatus(callApi('/api/products/' . $id, 'PUT', $bad, $admin), 422, 'Invalid update');
    }
    $updated = expectStatus(callApi('/api/products/' . $id, 'PUT', ['name' => 'Updated', 'price_minor' => 4567], $admin), 200, 'Update');
    check($updated['name'] === 'Updated' && (int) $updated['price_minor'] === 4567, 'Updated fields persisted');
    expectStatus(callApi('/api/products/' . $id, 'DELETE', token: $viewer), 403, 'Viewer cannot delete');
    if ($withExtensions) {
        expectStatus(callApi('/api/products/' . $id . '/urls', 'POST', ['url' => 'javascript:alert(1)'], $admin), 422, 'Unsafe URL rejected');
        $urls = expectStatus(callApi('/api/products/' . $id . '/urls', 'POST', ['url' => 'https://example.com/learning'], $admin), 201, 'URL added');
        $urlId = $urls['urls'][0]['id'];
        expectStatus(callApi('/api/products/0/urls/' . $urlId, 'DELETE', token: $admin), 404, 'URL belongs to its product');
        expectStatus(callApi('/api/products/' . $id . '/urls/' . $urlId, 'DELETE', token: $admin), 200, 'URL deleted');
    }
    expectStatus(callApi('/api/products/' . $id, 'DELETE', token: $admin), 200, 'Product deleted');
    $created = [];
    expectStatus(callApi('/api/products/' . $id, 'PUT', ['name' => 'Missing'], $admin), 404, 'Missing product');
    expectStatus(callApi('/api/auth/logout', 'POST', token: $viewer), 200, 'Logout');
    expectStatus(callApi('/api/products/list', token: $viewer), 401, 'Revoked token rejected');
    // 在本地数据库把本测试自己创建的令牌设为过期，验证服务端而不是前端计时。
    $pdo = require __DIR__ . '/database.php';
    $expire = $pdo->prepare('UPDATE learning_tokens SET expires_at = ? WHERE token_hash = ?');
    $expire->execute([time() - 1, hash('sha256', $admin)]);
    expectStatus(callApi('/api/products/list', token: $admin), 401, 'Expired token rejected');
    echo "API checks passed: $count\n";
} finally {
    // finally 是正常完成或抛异常后都要尝试执行的收尾；只清理本次记录。它不是数据库自动回滚。
    $pdo = require __DIR__ . '/database.php';
    foreach ($created as $id) {
        $delete = $pdo->prepare('DELETE FROM products WHERE id = ? AND sku LIKE ?');
        $delete->execute([$id, 'SMOKE-%']);
    }
    foreach ($tokens as $token) {
        $delete = $pdo->prepare('DELETE FROM learning_tokens WHERE token_hash = ?');
        $delete->execute([hash('sha256', $token)]);
    }
}
