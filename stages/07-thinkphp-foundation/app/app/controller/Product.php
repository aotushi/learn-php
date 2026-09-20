<?php
declare(strict_types=1);
namespace app\controller;

use app\service\ProductInput;
use think\facade\Db;
use think\Request;
use think\response\Json;
use think\exception\HttpException;

final class Product
{
    // Json 是 ThinkPHP 响应类；: Json 约束返回对象类型，不表示原始字符串。
    public function index(): Json
    {
        // name 选表，order 指排序(desc 降序)，select 执行多行查询，toArray 把结果集合转 PHP 数组。
        $products = Db::name('products')->order('id', 'desc')->select()->toArray();
        // 一次分组查询替代每件商品再查一次数据库。
        $counts = Db::name('product_urls')->field('product_id, COUNT(*) AS total')
            ->group('product_id')->select()->toArray();
        // field 指定列/表达式，COUNT(*) 计数，AS 起别名，group 按 product_id 分组。
        // array_column 取 total 列，并用 product_id 作为新数组键，便于直接查找。
        $byId = array_column($counts, 'total', 'product_id');
        // &$product 按引用修改原数组中的行，不是只修改副本；以后若复用此变量，应先 unset 解除引用。
        foreach ($products as &$product) {
            $product['url_count'] = (int) ($byId[$product['id']] ?? 0);
        }
        return json(['success' => true, 'data' => $products]);
    }

    public function create(Request $request): Json
    {
        // Request 对象由框架提供；post() 读取 POST 输入。validate 是本项目规则，true 表示创建。
        $data = ProductInput::validate($request->post(), true);
        if (Db::name('products')->where('sku', $data['sku'])->find()) {
            throw new HttpException(409, 'SKU 已存在。');
        }
        // insertGetId 插入并返回新主键；find($id) 读回一行；json 的第二个参数 201 是 HTTP 状态。
        $id = Db::name('products')->insertGetId($data);
        return json(['success' => true, 'data' => Db::name('products')->find($id)], 201);
    }

    public function update(Request $request, int $id): Json
    {
        // $this->find 是本控制器下方定义的方法；先确认存在，再用 put() 读取部分更新输入。
        $this->find($id);
        $data = ProductInput::validate($request->put(), false);
        // where 连用表示条件同时成立；SQL <> 是“不等于”，用于排除当前商品自身。
        if (isset($data['sku']) && Db::name('products')->where('sku', $data['sku'])->where('id', '<>', $id)->find()) {
            throw new HttpException(409, 'SKU 已存在。');
        }
        Db::name('products')->where('id', $id)->update($data);
        return json(['success' => true, 'data' => $this->find($id)]);
    }

    public function delete(int $id): Json
    {
        // delete 执行删除并返回影响行数；0 表示没有该商品。写操作由路由组中间件先检查权限。
        if (Db::name('products')->where('id', $id)->delete() === 0) {
            throw new HttpException(404, '商品不存在。');
        }
        return json(['success' => true, 'data' => null, 'message' => '商品已删除。']);
    }

    public function urls(int $id): Json
    {
        return json(['success' => true, 'data' => [
            'product' => $this->find($id),
            'urls' => Db::name('product_urls')->where('product_id', $id)->order('id', 'desc')->select()->toArray(),
        ]]);
    }

    public function addUrls(Request $request, int $id): Json
    {
        $this->find($id);
        // post(字段名, 默认值) 按字段读取；is_array 检查数组，array_is_list 进一步检查连续的 0 起始键。
        $urls = $request->post('urls', []);
        $single = $request->post('url');
        if (!is_array($urls) || !array_is_list($urls)) {
            throw new HttpException(422, 'urls 必须是网址数组。');
        }
        // $urls[] = 值 在列表末尾追加一个元素；并非访问一个叫“空字符串”的键。
        if ($single !== null) { $urls[] = $single; }
        if (count($urls) < 1 || count($urls) > 20) {
            throw new HttpException(422, '每次提交 1–20 个网址。');
        }
        foreach ($urls as &$url) {
            // FILTER_VALIDATE_URL 检查格式；parse_url + PHP_URL_SCHEME 提取协议，再限制 http/https。
            // (string) 转字符串，strtolower 转小写，strlen 计字节；这些检查不能由隐藏按钮替代。
            if (!is_string($url) || strlen($url) > 2048
                || !filter_var(trim($url), FILTER_VALIDATE_URL)
                || !in_array(strtolower((string) parse_url(trim($url), PHP_URL_SCHEME)), ['http', 'https'], true)) {
                throw new HttpException(422, '仅接受有效的 HTTP 或 HTTPS 网址。');
            }
            $url = trim($url);
        }
        // unset 解除循环变量与最后一项的引用；不删除该网址。array_unique 去重，array_values 重排列表键。
        unset($url);
        $urls = array_values(array_unique($urls));
        // map 将每个网址映射为数据库行；箭头函数自动按值捕获外层 $id，static 不等于不能捕获它。
        $rows = array_map(static fn (string $url): array => ['product_id' => $id, 'url' => $url], $urls);
        // insertAll 一次插入多行；added 返回数量，后面的查询返回当前商品的网址列表。
        Db::name('product_urls')->insertAll($rows);
        return json(['success' => true, 'data' => [
            'added' => count($rows),
            'urls' => Db::name('product_urls')->where('product_id', $id)->select()->toArray(),
        ]], 201);
    }

    public function deleteUrl(int $productId, int $urlId): Json
    {
        // 两个 where 同时验证网址 id 和所属商品，不能仅按网址 id 删除别人的关联记录。
        if (Db::name('product_urls')->where('id', $urlId)->where('product_id', $productId)->delete() === 0) {
            throw new HttpException(404, '商品网址不存在。');
        }
        return json(['success' => true, 'data' => null]);
    }

    private function find(int $id): array
    {
        // private 只给本类内部使用；?? throw 表示查询返回 null 时抛出 404，而不是返回空的成功响应。
        return Db::name('products')->find($id) ?? throw new HttpException(404, '商品不存在。');
    }
}
