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
        // header 读请求头。preg_match 匹配成功返回 1，不匹配返回 0，执行失败返回 false。
        // ^ 开头，$ 末尾，() 捕获令牌，[a-f0-9]{64} 限制 64 个小写十六进制字符，D 要求末尾严格结束。
        $header = $request->header('authorization', '');
        if (!preg_match('/^Bearer ([a-f0-9]{64})$/D', $header, $matches)) {
            throw new HttpException(401, '请先登录。');
        }
        // $matches[1] 是第一个捕获组；hash 用 SHA-256 算摘要，用于匹配数据库，不是加密后再解密。
        return hash('sha256', $matches[1]);
    }

    public static function user(Request $request): array
    {
        // self:: 调当前类方法；同时匹配摘要和未过期条件，身份来自数据库，而非客户端声称的角色。
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
        // 数组 + 是按键补充：左侧已有键保留。三元表达式给 admin 生成兼容标记，否则为 null。
        return $user + ['email' => '', 'admin_level' => $user['role'] === 'admin' ? 'super' : null,
            'created_by' => null];
    }
}
