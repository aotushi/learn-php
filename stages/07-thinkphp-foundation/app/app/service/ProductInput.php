<?php
declare(strict_types=1);
namespace app\service;

use InvalidArgumentException;

// 不依赖 HTTP 或数据库的输入规则，可以单独测试。
final class ProductInput
{
    // public static 可由类名直接调用；参数是输入数组与“是否创建”，返回新的已验证数组。
    public static function validate(array $input, bool $creating): array
    {
        if ($creating) {
            // 数组 += 是“补缺少的键”，保留输入已有的键；与数字 += 累加不同。
            // random_bytes 生成随机字节，bin2hex 转十六进制，strtoupper 转大写，组合出默认 SKU。
            $input += ['sku' => 'TP-' . strtoupper(bin2hex(random_bytes(4))),
                'name' => '', 'description' => '', 'price_minor' => 0,
                'stock_qty' => 0, 'currency' => 'CNY', 'status' => 'enabled'];
        }
        $result = [];
        foreach (['sku' => 64, 'name' => 255, 'description' => 4000,
                  'currency' => 3, 'status' => 16] as $field => $length) {
            // foreach 的 key => value 同时取字段名与长度上限；! 取反，continue 跳过本轮去下一项。
            // array_key_exists 检查键是否存在，值为 null 也算存在，因此仍须下面的类型检查。
            if (!array_key_exists($field, $input)) { continue; }
            if (!is_string($input[$field])) {
                throw new InvalidArgumentException("$field 必须是字符串。");
            }
            // trim 去掉两端空白；mb_strlen 按当前多字节编码统计字符，而 strlen 统计字节。
            $value = trim($input[$field]);
            if (mb_strlen($value) > $length || ($field !== 'description' && $value === '')) {
                throw new InvalidArgumentException("$field 长度不合法。");
            }
            $result[$field] = $value;
        }
        // isset 要求键存在且值不是 null；in_array 的第三个参数 true 要求严格类型比较。
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
            // filter_var 验证一个已有值；失败是 false，因此用 === false，不把合法 0 当失败。
            // || 会短路；前面的类型与格式检查通过后才进行 (int) 整数转换和范围比较。
            if ((!is_int($value) && !is_string($value))
                || filter_var($value, FILTER_VALIDATE_INT) === false
                || (int) $value < 0 || (int) $value > 2147483647) {
                throw new InvalidArgumentException("$field 必须是 0–2147483647 的整数。");
            }
            // 按字段键写入规范化后的整数；不直接返回未经验证的原输入。
            $result[$field] = (int) $value;
        }
        if ($result === []) { throw new InvalidArgumentException('没有可更新的商品字段。'); }
        return $result;
    }
}
