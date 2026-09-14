SET NAMES utf8mb4;

-- 当前数据库由调用方选择，不写死用户名或数据库名。

CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price_minor INT UNSIGNED NOT NULL DEFAULT 0,
    currency CHAR(3) NOT NULL DEFAULT 'CNY',
    stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
    status VARCHAR(16) NOT NULL DEFAULT 'enabled',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_products_name (name),
    INDEX idx_products_price (price_minor),
    INDEX idx_products_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_urls (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    url VARCHAR(2048) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_urls_product
        FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
    INDEX idx_product_urls_product_id (product_id)
) ENGINE=InnoDB;

INSERT IGNORE INTO products
    (sku, name, description, price_minor, currency, stock_qty, status)
VALUES
    ('LEARN-PHP-001', 'PHP 入门练习商品', '用于 PDO 和接口练习', 9900, 'CNY', 20, 'enabled'),
    ('LEARN-TP-001', 'ThinkPHP 后台练习商品', '用于 Vue 后台联调', 19900, 'CNY', 10, 'enabled');
