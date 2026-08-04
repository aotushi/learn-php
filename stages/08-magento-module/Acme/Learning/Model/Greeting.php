<?php

declare(strict_types=1);

namespace Acme\Learning\Model;

final class Greeting
{
    public function forName(string $name): string
    {
        $normalizedName = trim($name);

        return sprintf(
            'Hello, %s! Your first Magento module is working.',
            $normalizedName !== '' ? $normalizedName : 'Magento learner',
        );
    }
}
