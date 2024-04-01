<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use FC\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Zhxlan\Laradmin\Helpers\ApiResponse;

/**
 * 公共管理
 * SysmenuController class
 */
class CommonController extends BaseController
{
    /**
     * 版本号
     * sysVar function
     *
     * @return void
     */
    public function sysVar()
    {
        return ApiResponse::success(['version' => '1.69']);
    }

    /**
     * 生成验证码
     * @return void
     */
    public function captchaImg(Request $request){
        /* 实例化 */
        $captcha = new \Zhxlan\Laradmin\Helpers\Captcha();
        // 获取验证码
        $code = $captcha->getCode();
        Cache::put('NumberVerificationCode_'.$request->input('_'), $code, 300);
        /* 生成验证码图片 */
        // 输出验证码图片
        $captcha->doimg();
    }

    /**
     * 验证 验证码
     * @return void
     */
    public static function verifyCaptcha($code,$time){
        $CacheCoede = Cache::get('NumberVerificationCode_'.$time);
        Cache::forget('NumberVerificationCode_'.$time);
        return strtolower($CacheCoede) == strtolower($code) ? true :false;
    }

}
