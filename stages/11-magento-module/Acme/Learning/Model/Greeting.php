<?php

declare(strict_types=1);

namespace Acme\Learning\Model;

final class Greeting
{
    public function forName(string $name): string
    {
        // 纯 PHP 规则：trim 去两端空白，非空用输入名，否则使用默认名；sprintf 返回拼好的字符串。
        $normalizedName = trim($name);

        return sprintf(
            'Hello, %s! Your first Magento module is working.',
            $normalizedName !== '' ? $normalizedName : 'Magento learner',
        );
    }
}
