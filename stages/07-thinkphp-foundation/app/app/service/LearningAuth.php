<?php
declare(strict_types=1);
namespace app\service;

use think\facade\Db;
use think\Request;
use think\exception\HttpException;

final class LearningAuth
{
    public static function tokenHash(Request $request): string
    {
        $header = $request->header('authorization', '');
        if (!preg_match('/^Bearer ([a-f0-9]{64})$/D', $header, $matches)) {
            throw new HttpException(401, '请先登录。');
        }
        return hash('sha256', $matches[1]);
    }

    public static function user(Request $request): array
    {
        $token = Db::name('learning_tokens')->where('token_hash', self::tokenHash($request))
            ->where('expires_at', '>', time())->find();
        if (!$token) { throw new HttpException(401, '登录已过期，请重新登录。'); }
        $user = Db::name('learning_users')->field('id,username,role')->find($token['user_id']);
        if (!$user) { throw new HttpException(401, '账户不可用。'); }
        return $user;
    }

    public static function profile(array $user): array
    {
        // 保留已有 Vue 登录层需要的字段，不宣称支持其全部业务。
        return $user + ['email' => '', 'admin_level' => $user['role'] === 'admin' ? 'super' : null,
            'created_by' => null];
    }
}
