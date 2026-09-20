<?php
declare(strict_types=1);
namespace app\controller;

use app\service\LearningAuth;
use think\facade\Db;
use think\Request;
use think\exception\HttpException;
use think\response\Json;

final class Auth
{
    public function login(Request $request): Json
    {
        // post(字段, 默认值) 读请求；先检查类型，再用 strlen 检查字节长度，避免直接相信输入。
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        if (!is_string($username) || !is_string($password)
            || strlen($username) > 64 || strlen($password) > 72 || $password === '') {
            throw new HttpException(422, '用户名或密码格式不合法。');
        }
        $user = Db::name('learning_users')->where('username', $username)->find();
        // !$user 表示没查到用户；|| 短路，此时不读取不存在的 password_hash。
        // password_verify 用明文候选密码验证已存摘要，不是把摘要“解密”。
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new HttpException(401, '用户名或密码错误。');
        }
        // unset 从当前响应数组移除敏感字段，不会删除数据库中的密码摘要。
        unset($user['password_hash']);
        // 32 个随机字节转成 64 个十六进制字符；time() 是当前 Unix 秒数，3600 秒是一小时。
        $token = bin2hex(random_bytes(32));
        $expires = time() + 3600;
        // 数据库只保存令牌摘要，原令牌仅在本次登录响应返回。
        Db::name('learning_tokens')->where('expires_at', '<=', time())->delete();
        Db::name('learning_tokens')->insert([
            'token_hash' => hash('sha256', $token), 'user_id' => $user['id'], 'expires_at' => $expires,
        ]);
        return json(['success' => true, 'data' => [
            'user' => LearningAuth::profile($user),
            // gmdate 用 UTC 格式化时间，'c' 指 ISO 8601 格式；不是让浏览器决定令牌是否过期。
            'accessToken' => $token, 'expires' => gmdate('c', $expires),
        ]]);
    }

    public function profile(Request $request): Json
    {
        return json(['success' => true, 'data' => ['user' => LearningAuth::profile(LearningAuth::user($request))]]);
    }

    public function logout(Request $request): Json
    {
        // 先认证，再删除当前令牌的摘要记录；不删除用户账号，也不是只清空前端变量。
        LearningAuth::user($request);
        Db::name('learning_tokens')->where('token_hash', LearningAuth::tokenHash($request))->delete();
        return json(['success' => true, 'data' => null]);
    }
}
