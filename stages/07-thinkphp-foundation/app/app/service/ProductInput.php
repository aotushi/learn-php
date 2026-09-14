<?php
declare(strict_types=1);
namespace app\service;

use InvalidArgumentException;

// 不依赖 HTTP 或数据库的输入规则，可以单独测试。
final class ProductInput
{
    public static function validate(array $input, bool $creating): array
    {
        if ($creating) {
            $input += ['sku' => 'TP-' . strtoupper(bin2hex(random_bytes(4))),
                'name' => '', 'description' => '', 'price_minor' => 0,
                'stock_qty' => 0, 'currency' => 'CNY', 'status' => 'enabled'];
        }
        $result = [];
        foreach (['sku' => 64, 'name' => 255, 'description' => 4000,
                  'currency' => 3, 'status' => 16] as $field => $length) {
            if (!array_key_exists($field, $input)) { continue; }
            if (!is_string($input[$field])) {
                throw new InvalidArgumentException("$field 必须是字符串。");
            }
            $value = trim($input[$field]);
            if (mb_strlen($value) > $length || ($field !== 'description' && $value === '')) {
                throw new InvalidArgumentException("$field 长度不合法。");
            }
            $result[$field] = $value;
        }
        if (isset($result['currency'])) {
            $result['currency'] = strtoupper($result['currency']);
            if (!in_array($result['currency'], ['CNY', 'USD', 'EUR'], true)) {
                throw new InvalidArgumentException('本练习支持 CNY、USD、EUR。');
            }
        }
        if (isset($result['status']) && !in_array($result['status'], ['enabled', 'disabled'], true)) {
            throw new InvalidArgumentException('status 只能是 enabled 或 disabled。');
        }
        foreach (['price_minor', 'stock_qty'] as $field) {
            if (!array_key_exists($field, $input)) { continue; }
            $value = $input[$field];
            // 接收 JSON 整数或表单整数字符串；拒绝 bool、float、数组及超范围值。
            if ((!is_int($value) && !is_string($value))
                || filter_var($value, FILTER_VALIDATE_INT) === false
                || (int) $value < 0 || (int) $value > 2147483647) {
                throw new InvalidArgumentException("$field 必须是 0–2147483647 的整数。");
            }
            $result[$field] = (int) $value;
        }
        if ($result === []) { throw new InvalidArgumentException('没有可更新的商品字段。'); }
        return $result;
    }
}
