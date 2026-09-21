<?php
declare(strict_types=1);

// 本练习约定：缺少 quantity 时使用默认值 1；传了空值则视为错误。
// filter_input 是本练习的主要取值方式：普通值返回字符串，缺少时返回 null，
// quantity[]=1 是数组输入，但本次要求单个值，因此返回 false。
$rawQuantity = filter_input(INPUT_GET, 'quantity', FILTER_UNSAFE_RAW);

// 另一种方案能保留数组原形；这里只作为对照，不参与执行：
// $rawQuantity = $_GET['quantity'] ?? null;
// if (is_array($rawQuantity)) { $rawQuantity = false; }

// 在另一个终端运行 docker compose logs -f --tail 20 web，可实时观察取值结果。
error_log('quantity from filter_input: ' . json_encode($rawQuantity, JSON_UNESCAPED_UNICODE));

$quantity = 1;
$error = null;

if ($rawQuantity === false) {
    $error = 'quantity 必须是单个整数，不能是数组。';
} elseif ($rawQuantity !== null) {
    if (trim($rawQuantity) === '') {
        $error = 'quantity 不能为空，必须是 1 到 10 之间的整数。';
    } else {
        // filter_var 验证字符串能否完整表示整数；成功返回 int，失败返回 false。
        $validatedQuantity = filter_var(trim($rawQuantity), FILTER_VALIDATE_INT);

        // 必须用 === false，因为合法整数 0 在普通真假判断中也会被当成 false。
        if ($validatedQuantity === false) {
            $error = 'quantity 必须是整数。';
        } elseif ($validatedQuantity < 1 || $validatedQuantity > 10) {
            $error = 'quantity 必须是 1 到 10 之间的整数。';
        } else {
            $quantity = $validatedQuantity;
        }
    }
}

header('Content-Type: application/json; charset=utf-8');

// 下面两个 json_encode 的第二个参数用于开启编码选项：
// JSON_UNESCAPED_UNICODE 让中文直接显示，不转换成 \u6570\u91cf 这样的 Unicode 转义；
// JSON_THROW_ON_ERROR 让编码失败时抛出 JsonException，而不是只返回 false；
// | 是“按位或”，用于把两个选项合并，让它们同时生效。
if ($error !== null) {
    // 422 表示请求已经被服务器理解，但参数内容不符合本接口的约定。
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'error' => $error,
    ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

// 未设置错误状态时，HTTP 默认状态为 200。
echo json_encode([
    'success' => true,
    'data' => [
        'quantity' => $quantity,
    ],
], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
