<?php
declare(strict_types=1);

// 集成测试只创建随机 SKU 的临时商品，finally 中删除；不删除种子或学习者数据。
$base = getenv('API_BASE_URL') ?: 'http://thinkphp:8000';
$count = 0;
$tokens = [];
$created = [];

function callApi(string $path, string $method = 'GET', ?array $body = null, string $token = ''): array
{
    global $base;
    $curl = curl_init($base . $path);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer ' . $token],
    ]);
    if ($body !== null) { curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body, JSON_THROW_ON_ERROR)); }
    $text = curl_exec($curl);
    if ($text === false) { throw new RuntimeException(curl_error($curl)); }
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    return [$status, json_decode($text, true, 512, JSON_THROW_ON_ERROR)];
}

function check(bool $condition, string $message): void
{
    global $count;
    if (!$condition) { throw new RuntimeException($message); }
    echo 'PASS ' . ++$count . ': ' . $message . "\n";
}

function expectStatus(array $response, int $expected, string $message): array
{
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
    expectStatus(callApi('/api/products/' . $id . '/urls', 'POST', ['url' => 'javascript:alert(1)'], $admin), 422, 'Unsafe URL rejected');
    $urls = expectStatus(callApi('/api/products/' . $id . '/urls', 'POST', ['url' => 'https://example.com/learning'], $admin), 201, 'URL added');
    $urlId = $urls['urls'][0]['id'];
    expectStatus(callApi('/api/products/0/urls/' . $urlId, 'DELETE', token: $admin), 404, 'URL belongs to its product');
    expectStatus(callApi('/api/products/' . $id . '/urls/' . $urlId, 'DELETE', token: $admin), 200, 'URL deleted');
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
