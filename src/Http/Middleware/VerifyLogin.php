<?php


namespace Zhxlan\Laradmin\Http\Middleware;

use App\Models\Sys\SysUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 登录验证中间件
 */
class VerifyLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationHeader = $request->header('authorization');
        // 检查是否存在 Authorization 头
        if ($authorizationHeader) {
            // 将 Authorization 头拆分为其组成部分
            $headerParts = explode(' ', $authorizationHeader);

            // 检查头部是否至少有两部分（类型和值）
            if (count($headerParts) == 2) {
                // 提取类型和值
                $type = $headerParts[0];
                $value = $headerParts[1];
                // 现在你可以根据需要使用 $categories 和 $value
                // 例如，如果是 Bearer token，可以进行进一步处理
                if (strtolower($type) === 'bearer') {
                    $sysUserInfo = SysUser::where('token', $value)->first();
                    if ($sysUserInfo) {
                        // 验证 token_expiration_at 是否已过当前时间
                        $currentDateTime = now();
                        $expirationDateTime = $sysUserInfo->token_expiration_at;
                        if ($currentDateTime < $expirationDateTime) {
                            $request->merge(['global' => ['user' => $sysUserInfo->toArray()]]);

                            return $next($request);
                        } else {
                            // token 已过期
                            return response()->json(['error' => 'Token 已过期'], 401);
                        }
                    } else {
                        return response()->json(['error' => '无效token'], 401);
                    }
                } else {
                    // 如果需要，处理其他类型的 Authorization 头
                    return response()->json(['error' => '登录信息已过期'], 401);
                }
            } else {
                // 无效的 Authorization 头格式
                // 相应处理
                return response()->json(['error' => '登录信息已过期'], 401);
            }
        } else {
            // Authorization 头不存在
            // 返回状态码 2009
            return response()->json(['error' => '登录信息已过期'], 401);
        }
    }
}
