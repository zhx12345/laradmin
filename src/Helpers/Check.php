<?php

namespace Zhxlan\Laradmin\Helpers;

/**
 * 验证类
 */
class Check
{
    /**
     * 密码检测强度
     *
     * @return void
     */
    public static function passwordDetection($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password); // 一个大写字母
        $lowercase = preg_match('@[a-z]@', $password); // 一个小写字母
        $number = preg_match('@[0-9]@', $password); // 一个数字
        $specialChars = preg_match('@[^\w]@', $password); // 一个特殊字符
        if (! $uppercase || ! $lowercase || ! $number || strlen($password) < 8 || ! $specialChars) {
            return ['code' => false, 'msg' => '密码长度应至少为8个字符，并且应至少包含一个大写和小写字母、数字、特殊符号'];
        } else {
            return ['code' => true, 'msg' => '验证通过'];
        }
    }

    /**
     * 随机生成字符串
     *
     * @return string
     */
    public static function getRandStr($length)
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }

        return $randstr;
    }

    /**
     * 判断文件类型
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-11
     *
     * @Time: 11:27
     *
     * @Interface getFileType
     *
     * @return array
     */
    public static function getFileType($extension, $mimeType)
    {
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) || str_contains($mimeType, 'image')) {
            return ['categories' => 1, 'extName' => '图片', 'ext' => 'image'];
        } elseif (in_array($extension, ['zip', 'rar', '7z']) || str_contains($mimeType, 'zip')) {
            return ['categories' => 2, 'extName' => '压缩包', 'ext' => 'zip'];
        } elseif (in_array($extension, ['mp4', 'mov', 'avi']) || str_contains($mimeType, 'video')) {
            return ['categories' => 3, 'extName' => '视频', 'ext' => 'video'];
        } elseif (in_array($extension, ['mp3', 'wav', 'ogg']) || str_contains($mimeType, 'audio')) {
            return ['categories' => 4, 'extName' => '音频', 'ext' => 'audio'];
        } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx']) || str_contains($mimeType, 'application')) {
            return ['categories' => 5, 'extName' => '文档', 'ext' => 'application'];
        } else {
            return ['categories' => 6, 'extName' => '未知', 'ext' => 'unknown'];
        }
    }

    /**
     * 手机号码格式验证
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 15:49
     *
     * @Interface isValidPhoneNumber
     *
     * @return bool
     */
    public static function isValidPhoneNumber($phoneNumber)
    {
        // 使用正则表达式验证手机号码格式
        $pattern = '/^1[3456789]\d{9}$/';

        // 执行正则匹配
        if (preg_match($pattern, $phoneNumber)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证身份证号码格式
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:02
     *
     * @Interface isValidChineseIDCardNumber
     *
     * @return bool
     */
    public static function isValidChineseIDCardNumber($idCardNumber)
    {
        // 使用正则表达式验证身份证号码格式
        $pattern = '/^[1-9]\d{5}(19|20)\d{2}(0[1-9]|1[0-2])(0[1-9]|[1-2]\d|3[0-1])\d{3}(\d|X)$/';

        if (preg_match($pattern, $idCardNumber)) {
            // 验证校验码是否正确
            // 验证身份证号码的校验码是否正确
            $wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
            $ai = str_split(substr($idCardNumber, 0, 17));
            $n = 0;

            for ($i = 0; $i < 17; $i++) {
                $n += $wi[$i] * $ai[$i];
            }

            $c = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
            $checkCode = $c[$n % 11];

            if ($checkCode === substr($idCardNumber, -1)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 验证座机号码格式
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:07
     *
     * @Interface isValidLandlinePhoneNumber
     *
     * @return bool
     */
    public static function isValidLandlinePhoneNumber($phoneNumber)
    {
        // 使用正则表达式验证座机号码格式
        // 中国大陆座机号码格式示例：区号-号码或区号号码
        $pattern = '/^\d{3}-\d{7,8}$|^\d{4}-\d{7,8}$|^\d{7,8}$/';
        // 执行正则匹配
        if (preg_match($pattern, $phoneNumber)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证邮箱格式
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:08
     *
     * @Interface isValidEmail
     *
     * @return bool
     */
    public static function isValidEmail($email)
    {
        // 使用正则表达式验证邮箱格式
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        // 执行正则匹配
        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证url格式
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:25
     *
     * @Interface checkUrl
     *
     * @return bool
     */
    public static function isValidkUrl($url)
    {
        if (! preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $url)) {
            return false;
        }

        return true;
    }

    /**
     * 用于判断当前请求是否来自微信浏览器
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:11
     *
     * @Interface isValidWeixin
     *
     * @return false
     */
    public static function isValidWeixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 判断是否为手机访问
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:40
     *
     * @Interface is_mobile
     *
     * @return bool
     */
    public static function isValidMobile()
    {

        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if (isset($_SERVER['HTTP_CLIENT']) && $_SERVER['HTTP_CLIENT'] == 'PhoneClient') {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        }
        //判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile',
            ];
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match('/('.implode('|', $clientkeywords).')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }

        return false;

    }

    /**
     * 验证输入的内容是否为纯数字
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:23
     *
     * @Interface isValidNumeric
     *
     * @return bool
     */
    public static function isValidNumeric($number)
    {
        if (! is_numeric($number)) {
            return false;
        }

        return preg_match('/^\d+$/i', $number) ? true : false;
    }

    /**
     * 验证是否全是中文
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:25
     *
     * @Interface checkCn
     *
     * @return bool
     */
    public static function isAllChineseCharacters($str)
    {
        // 使用正则表达式匹配是否全部为中文字符
        $pattern = '/^[\x{4e00}-\x{9fa5}]+$/u';

        // 执行正则匹配
        if (preg_match($pattern, $str)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 验证是否全是字母
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:26
     *
     * @Interface checkEn
     *
     * @return bool
     */
    public static function isValidEn($str)
    {
        if (preg_match('/^[a-zA-Z]+$/', "$str")) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断是否为空数组
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:24
     *
     * @Interface isEmptyArray
     *
     * @return bool
     */
    public static function isEmptyArray($arr)
    {
        if (empty($arr) || ! is_array($arr) || count($arr) == 0) {
            return true;
        }

        return false;
    }

    /**
     * 判断是否为空数据
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:24
     *
     * @Interface isEmpty
     *
     * @return bool
     */
    public static function isEmpty($data)
    {
        if (empty($data)) {
            return true;
        }

        return false;
    }

    /**
     * 过滤script 脚本
     *
     * @Notes:
     *
     * @Author: zhx
     *
     * @Date: 2023-07-18
     *
     * @Time: 16:39
     *
     * @Interface isValidScript
     *
     * @return array|string|string[]|null
     */
    public static function isValidScript($str)
    {
        $preg = "/<script[\s\S]*?<\/script>/i";

        return preg_replace($preg, '', $str);
    }

    // TODO

}
