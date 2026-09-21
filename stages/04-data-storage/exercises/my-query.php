<?php
// 使用下方连接入口，自己写查询：只取 sku/name/price_minor，status=enabled 且 price_minor>=5000，两个值都绑定，按 price_minor、id 升序。这里只读，不插入测试行。

// 从 exercises 向上三级回根目录；这个文件返回 PDO，不直接打印。
// $pdo = require dirname(__DIR__, 3) . '/scripts/database.php';
// 在这里写自己的 prepare、execute 和结果输出。

$pdo = require dirname(__DIR__, 3) . '/scripts/database.php';

$status_val = 'enabled';
$minimum_price = 9901;

$statement = $pdo->prepare(
  'SELECT sku, name, price_minor
  FROM products
  WHERE status = :status AND price_minor >= :minimum_price
  ORDER BY price_minor ASC, id ASC'
);

$statement->execute(
  [
    'minimum_price' => $minimum_price,
    'status' => $status_val
  ]
);


foreach ($statement as $product) {
  printf(
    "%s | %s | %d 分\n",
    $product['sku'],
    $product['name'],
    $product['price_minor'],
  );
}
