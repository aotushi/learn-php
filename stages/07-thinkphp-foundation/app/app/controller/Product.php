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
    public function index(): Json
    {
        $products = Db::name('products')->order('id', 'desc')->select()->toArray();
        // 一次分组查询替代每件商品再查一次数据库。
        $counts = Db::name('product_urls')->field('product_id, COUNT(*) AS total')
            ->group('product_id')->select()->toArray();
        $byId = array_column($counts, 'total', 'product_id');
        foreach ($products as &$product) {
            $product['url_count'] = (int) ($byId[$product['id']] ?? 0);
        }
        return json(['success' => true, 'data' => $products]);
    }

    public function create(Request $request): Json
    {
        $data = ProductInput::validate($request->post(), true);
        if (Db::name('products')->where('sku', $data['sku'])->find()) {
            throw new HttpException(409, 'SKU 已存在。');
        }
        $id = Db::name('products')->insertGetId($data);
        return json(['success' => true, 'data' => Db::name('products')->find($id)], 201);
    }

    public function update(Request $request, int $id): Json
    {
        $this->find($id);
        $data = ProductInput::validate($request->put(), false);
        if (isset($data['sku']) && Db::name('products')->where('sku', $data['sku'])->where('id', '<>', $id)->find()) {
            throw new HttpException(409, 'SKU 已存在。');
        }
        Db::name('products')->where('id', $id)->update($data);
        return json(['success' => true, 'data' => $this->find($id)]);
    }

    public function delete(int $id): Json
    {
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
        $urls = $request->post('urls', []);
        $single = $request->post('url');
        if (!is_array($urls) || !array_is_list($urls)) {
            throw new HttpException(422, 'urls 必须是网址数组。');
        }
        if ($single !== null) { $urls[] = $single; }
        if (count($urls) < 1 || count($urls) > 20) {
            throw new HttpException(422, '每次提交 1–20 个网址。');
        }
        foreach ($urls as &$url) {
            if (!is_string($url) || strlen($url) > 2048
                || !filter_var(trim($url), FILTER_VALIDATE_URL)
                || !in_array(strtolower((string) parse_url(trim($url), PHP_URL_SCHEME)), ['http', 'https'], true)) {
                throw new HttpException(422, '仅接受有效的 HTTP 或 HTTPS 网址。');
            }
            $url = trim($url);
        }
        unset($url);
        $urls = array_values(array_unique($urls));
        $rows = array_map(static fn (string $url): array => ['product_id' => $id, 'url' => $url], $urls);
        Db::name('product_urls')->insertAll($rows);
        return json(['success' => true, 'data' => [
            'added' => count($rows),
            'urls' => Db::name('product_urls')->where('product_id', $id)->select()->toArray(),
        ]], 201);
    }

    public function deleteUrl(int $productId, int $urlId): Json
    {
        if (Db::name('product_urls')->where('id', $urlId)->where('product_id', $productId)->delete() === 0) {
            throw new HttpException(404, '商品网址不存在。');
        }
        return json(['success' => true, 'data' => null]);
    }

    private function find(int $id): array
    {
        return Db::name('products')->find($id) ?? throw new HttpException(404, '商品不存在。');
    }
}
