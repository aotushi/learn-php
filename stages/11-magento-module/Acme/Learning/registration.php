<?php

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

// 第二阶段阅读：Magento 类的静态方法登记“模块类型、模块名、模块目录”；不是 PHP 内置注册函数。
ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Acme_Learning',
    __DIR__,
);
