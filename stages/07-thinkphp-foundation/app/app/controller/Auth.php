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
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        if (!is_string($username) || !is_string($password)
            || strlen($username) > 64 || strlen($password) > 72 || $password === '') {
            throw new HttpException(422, '用户名或密码格式不合法。');
        }
        $user = Db::name('learning_users')->where('username', $username)->find();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new HttpException(401, '用户名或密码错误。');
        }
        unset($user['password_hash']);
        $token = bin2hex(random_bytes(32));
        $expires = time() + 3600;
        // 数据库只保存令牌摘要，原令牌仅在本次登录响应返回。
        Db::name('learning_tokens')->where('expires_at', '<=', time())->delete();
        Db::name('learning_tokens')->insert([
            'token_hash' => hash('sha256', $token), 'user_id' => $user['id'], 'expires_at' => $expires,
        ]);
        return json(['success' => true, 'data' => [
            'user' => LearningAuth::profile($user),
            'accessToken' => $token, 'expires' => gmdate('c', $expires),
        ]]);
    }

    public function profile(Request $request): Json
    {
        return json(['success' => true, 'data' => ['user' => LearningAuth::profile(LearningAuth::user($request))]]);
    }

    public function logout(Request $request): Json
    {
        LearningAuth::user($request);
        Db::name('learning_tokens')->where('token_hash', LearningAuth::tokenHash($request))->delete();
        return json(['success' => true, 'data' => null]);
    }
}
