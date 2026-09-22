<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__) . '/stages/01-php-syntax/exercises/my-price.php';

final class MyPriceInputTest extends TestCase
{
    public function testIntegerBoundariesAreAccepted(): void
    {
        // assertSame(预期, 实际) 也比较类型：返回字符串 "0" 不等于整数 0。
        self::assertSame(0, readPriceMinor(['price_minor' => 0]));
        self::assertSame(1, readPriceMinor(['price_minor' => 1]));
        self::assertSame(100_000_000, readPriceMinor(['price_minor' => 100_000_000]));
    }

    public function testDigitStringsIncludingLeadingZerosAreAccepted(): void
    {
        // 引号表示输入原本是字符串；函数应验证后返回整数。
        self::assertSame(0, readPriceMinor(['price_minor' => '0']));
        self::assertSame(0, readPriceMinor(['price_minor' => '00']));
        self::assertSame(9900, readPriceMinor(['price_minor' => '9900']));
        self::assertSame(99, readPriceMinor(['price_minor' => '0099']));
        self::assertSame(100_000_000, readPriceMinor(['price_minor' => '100000000']));
    }

    public function testInvalidInputsAreRejected(): void
    {
        // 每项都是传给函数的完整数组；[] 表示缺少 price_minor 这个键。
        $invalidInputs = [
            'missing key' => [],
            'null' => ['price_minor' => null],
            'true' => ['price_minor' => true],
            'false' => ['price_minor' => false],
            'float' => ['price_minor' => 1.5],
            'array' => ['price_minor' => []],
            'empty string' => ['price_minor' => ''],
            'space' => ['price_minor' => ' '],
            'surrounding spaces' => ['price_minor' => ' 9 '],
            'plus sign' => ['price_minor' => '+9'],
            'negative string' => ['price_minor' => '-1'],
            'decimal string' => ['price_minor' => '9.9'],
            'exponent' => ['price_minor' => '1e2'],
            'non-ASCII digit' => ['price_minor' => '９'],
            'negative integer' => ['price_minor' => -1],
            'integer above maximum' => ['price_minor' => 100_000_001],
            'string above maximum' => ['price_minor' => '100000001'],
        ];

        foreach ($invalidInputs as $case => $input) {
            try {
                readPriceMinor($input);
                // 没有抛异常就执行到这里，让 PHPUnit 报告是哪一项被误接受。
                self::fail("{$case} was accepted");
            } catch (InvalidArgumentException $error) {
                // 预期结果是这种异常，不是一个返回值；TypeError 等其它错误仍会使测试失败。
                // 第三个参数 $case 会在断言失败时标明是哪组输入。
                self::assertInstanceOf(InvalidArgumentException::class, $error, $case);
            }
        }
    }
}
