<?php

namespace Zhxlan\Laradmin\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Zhxlan\Laradmin\Models\Sys\SysUser;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        $auth = self::shouldPassThrough($request);
        if ($auth['result']) {
            $request->merge(['global' => $auth['user']]);
            return $next($request);
        } else {
            return response()->json(['error' => $auth['error']], $auth['status']);
        }
    }


    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public static function shouldPassThrough($request)
    {
        $authorizationHeader = $request->header('authorization');
        // 检查是否存在 Authorization 头
        if (!$authorizationHeader) {
            // Authorization 头不存在
            // 返回状态码 2009
            return ['error' => '登录信息已过期', 'status' => 401, 'result' => false];
        }
        // 将 Authorization 头拆分为其组成部分
        $headerParts = explode(' ', $authorizationHeader);
        // 检查头部是否至少有两部分（类型和值）
        if (count($headerParts) !== 2) {
            // 无效的 Authorization 头格式
            // 相应处理
            return ['error' => '登录信息已过期', 'status' => 401, 'result' => false];
        }

        // 提取类型和值
        $type = $headerParts[0];
        $value = $headerParts[1];
        // 现在你可以根据需要使用 $categories 和 $value
        // 例如，如果是 Bearer token，可以进行进一步处理
        if (strtolower($type) !== 'bearer') {
            return ['error' => '登录信息已过期', 'status' => 401, 'result' => false];
        }

        $sysUserInfo = SysUser::where('token', $value)->first();
        if (!$sysUserInfo) {
            return ['error' => '无效token', 'status' => 401, 'result' => false];
        }

        // 验证 token_expiration_at 是否已过当前时间
        $currentDateTime = now();
        $expirationDateTime = $sysUserInfo->token_expiration_at;
        if ($currentDateTime >= $expirationDateTime) {
            // token 已过期
            return ['error' => 'Token 已过期', 'status' => 401, 'result' => false];
        }

        return ['user' => $sysUserInfo->toArray(), 'result' => true];
    }


}
