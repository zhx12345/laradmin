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
        $ver = new Captcha();
        // 验证码宽度
        $ver->width = 320;
        // 验证码高度
        $ver->height = 120;
        // 验证码个数
        $ver->nums = 3;
        // 随机字符串
        //$ver->random = '舔狗不得好死';
        // 随机数大小
        $ver->font_size = 50;
        // 是否为动态验证码
        $ver->is_gif = false;
        // 动图帧数
        $ver->gif_fps = 10;
        /* 生成验证码 */
        $code = $ver->getCode();
        // 保存验证码缓存，有效期 300 秒
        Cache::put('NumberVerificationCode_'.$request->input('_'), $code, 300);
        /* 生成验证码图片 */
        $ver->doImg($code);
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
