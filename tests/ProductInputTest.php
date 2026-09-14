<?php
declare(strict_types=1);
namespace LearnPhp\Tests;
use PHPUnit\Framework\TestCase;
use app\service\ProductInput;

require_once __DIR__ . '/../stages/07-thinkphp-foundation/app/app/service/ProductInput.php';

final class ProductInputTest extends TestCase
{
    public function testDefaultsAndCurrencyNormalization(): void
    {
        $data = ProductInput::validate(['name' => '  Demo ', 'currency' => 'usd'], true);
        self::assertSame('Demo', $data['name']);
        self::assertSame('USD', $data['currency']);
        self::assertSame(0, $data['price_minor']);
        self::assertSame(['price_minor' => 12], ProductInput::validate(['price_minor' => '12'], false));
    }

    public function testInvalidInputsAreRejected(): void
    {
        foreach ([['name' => []], ['name' => ''], ['name' => str_repeat('中', 256)],
            ['sku' => str_repeat('x', 65)], ['currency' => 'BAD'], ['status' => 'deleted'],
            ['price_minor' => -1], ['stock_qty' => true], ['price_minor' => 1.5],
            ['price_minor' => 2147483648], ['price_minor' => []], []] as $input) {
            try {
                ProductInput::validate($input, false);
                self::fail('Expected validation failure for ' . json_encode($input));
            } catch (\InvalidArgumentException $e) {
                self::assertNotEmpty($e->getMessage());
            }
        }
    }
}
