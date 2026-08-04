CREATE DATABASE IF NOT EXISTS learning
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON learning.* TO 'magento'@'%';

USE learning;

CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(64) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    price_minor INT UNSIGNED NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'CNY',
    stock_qty INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_products_name (name),
    INDEX idx_products_price (price_minor)
) ENGINE=InnoDB;

INSERT IGNORE INTO products (sku, name, price_minor, currency, stock_qty) VALUES
    ('LEARN-PHP-001', 'PHP 入门练习商品', 9900, 'CNY', 20),
    ('LEARN-MAGE-001', 'Magento 模块练习商品', 29900, 'CNY', 10);
