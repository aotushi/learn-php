<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/stages/01-php-syntax/exercises/my-price.php';

final class MyPriceInputTest extends TestCase
{
  public function testZeroIsAccepted(): void
  {
    self::assertSame(0, readPriceMinor(['price_minor' => 0]));
  }

  public function testNegativeIsRejected(): void
  {
    $this->expectException(InvalidArgumentException::class);
    readPriceMinor(['price_minor' => -1]);
  }
}
