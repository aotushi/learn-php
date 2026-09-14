<?php

declare(strict_types=1);

$host = getenv('DB_HOST') ?: 'mysql';
$port = getenv('DB_PORT') ?: '3306';
$database = getenv('DB_DATABASE') ?: 'learning';
$username = getenv('DB_USERNAME') ?: 'learnphp';
$password = getenv('DB_PASSWORD') ?: 'learnphp-local-only';

$pdo = new PDO(
    "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
    $username,
    $password,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
);

$minimumPrice = 5000;
$statement = $pdo->prepare(
    'SELECT sku, name, price_minor, currency, stock_qty
     FROM products
     WHERE price_minor >= :minimum_price
     ORDER BY price_minor ASC',
);
$statement->execute(['minimum_price' => $minimumPrice]);

foreach ($statement as $product) {
    printf(
        "%s | %s | %s %.2f | 库存 %d\n",
        $product['sku'],
        $product['name'],
        $product['currency'],
        $product['price_minor'] / 100,
        $product['stock_qty'],
    );
}
