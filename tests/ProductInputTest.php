<?php
declare(strict_types=1);
namespace LearnPhp\Tests;
use PHPUnit\Framework\TestCase;
use app\service\ProductInput;

// require_once 执行文件但避免重复载入；这里单独加载规则类，不启动整套 ThinkPHP 或数据库。
require_once __DIR__ . '/../stages/07-thinkphp-foundation/app/app/service/ProductInput.php';

final class ProductInputTest extends TestCase
{
    public function testDefaultsAndCurrencyNormalization(): void
    {
        // ProductInput::validate 是项目自己的静态方法；true 表示创建时补默认值，false 表示部分更新。
        $data = ProductInput::validate(['name' => '  Demo ', 'currency' => 'usd'], true);
        self::assertSame('Demo', $data['name']);
        self::assertSame('USD', $data['currency']);
        self::assertSame(0, $data['price_minor']);
        self::assertSame(['price_minor' => 12], ProductInput::validate(['price_minor' => '12'], false));
    }

    public function testInvalidInputsAreRejected(): void
    {
        // str_repeat(字符串, 次数) 构造超长输入；外层列表让同一段测试逐项检查坏输入。
        foreach ([['name' => []], ['name' => ''], ['name' => str_repeat('中', 256)],
            ['sku' => str_repeat('x', 65)], ['currency' => 'BAD'], ['status' => 'deleted'],
            ['price_minor' => -1], ['stock_qty' => true], ['price_minor' => 1.5],
            ['price_minor' => 2147483648], ['price_minor' => []], []] as $input) {
            try {
                ProductInput::validate($input, false);
                // 到达这里表示“应该拒绝却没拒绝”，主动 fail；不是修改断言让错误通过。
                self::fail('Expected validation failure for ' . json_encode($input));
            } catch (\InvalidArgumentException $e) {
                // 这里只捕获被测的输入异常；assertNotEmpty 要求错误信息不是空的。
                self::assertNotEmpty($e->getMessage());
            }
        }
    }
}
