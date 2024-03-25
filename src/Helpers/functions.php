<?php

declare(strict_types=1);

// TODO 应用公共文件



if (!function_exists('getter')) {
    /**
     * 获取数组的下标值
     * @param array $data 数据源
     * @param string $field 字段名称
     * @param string $default 默认值
     * @return mixed|string 返回结果
     */
    function getter($data, $field, $default = '')
    {
        $result = $default;
        if (isset($data[$field])) {
            $result = $data[$field];
        }
        return $result;
    }
}

if (!function_exists('array_sort')) {
    /**
     * 数组排序
     *
     * @param array $arr 数据源
     * @param $keys KEY
     * @param bool $desc 排序方式（默认：asc）
     * @return array 返回结果
     */
    function array_sort($arr, $keys, $desc = false)
    {
        $key_value = $new_array = [];
        foreach ($arr as $k => $v) {
            $key_value[$k] = $v[$keys];
        }
        if ($desc) {
            arsort($key_value);
        } else {
            asort($key_value);
        }
        reset($key_value);
        foreach ($key_value as $k => $v) {
            $new_array[$k] = $arr[$k];
        }

        return $new_array;
    }
}

if (!function_exists('get_random_code')) {
    /**
     * 获取指定位数的随机码
     *
     * @param int $num 随机码长度
     * @return string 返回字符串
     */
    function get_random_code($num = 12)
    {
        $codeSeeds = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codeSeeds .= 'abcdefghijklmnopqrstuvwxyz';
        $codeSeeds .= '0123456789_';
        $len = strlen($codeSeeds);
        $code = '';
        for ($i = 0; $i < $num; $i++) {
            $rand = rand(0, $len - 1);
            $code .= $codeSeeds[$rand];
        }

        return $code;
    }
}

if (!function_exists('get_order_num')) {
    /**
     * 生成订单号
     *
     * @param string $prefix 订单前缀(如：JD-)
     * @return string 输出订单号字符串
     */
    function get_order_num($prefix = '')
    {
        $micro = substr(microtime(), 2, 3);

        return $prefix . date('YmdHis') . $micro . rand(100000, 999999);
    }
}

if (!function_exists('get_token')) {
    /**
     * 获取唯一Token
     *
     * @param string $userName 用户名
     */
    function get_token(string $userName): string
    {
        $str = md5(uniqid(md5(strval(microtime(true))), true));

        return sha1($str . $userName);
    }
}

if (!function_exists('current_date')) {

    /**
     * 获取当前日期
     *
     * @param string $type 类型
     */
    function current_date(string $type = 'dateTime'): string
    {
        if ($type == 'dateTime') {
            return date('Y-m-d H:i:s');
        } elseif ($type == 'time') {
            return date('H:i:s');
        } else {
            return date('Y-m-d');
        }
    }
}


if (!function_exists('get_time')) {
    /**
     * 根据0 - 24 获取时间戳
     * @Notes:
     * @Interface periodTime
     * @return array
     */
    function get_time($number = 0)
    {
        // 获取当前时间
        $currentDateTime = \Illuminate\Support\Carbon::now();
        // 增加24小时
        $newDateTime = $currentDateTime->addHours($number);

        return $newDateTime;
    }
}

if (!function_exists('get_zodiac_sign')) {

    /**
     * 根据月、日获取星座
     *
     * @param $month 月份
     * @param $day 日期
     * @return string 返回结果
     */
    function get_zodiac_sign($month, $day)
    {
        // 检查参数有效性
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return false;
        }

        // 星座名称以及开始日期
        $signs = [
            ['20' => '水瓶座'],
            ['19' => '双鱼座'],
            ['21' => '白羊座'],
            ['20' => '金牛座'],
            ['21' => '双子座'],
            ['22' => '巨蟹座'],
            ['23' => '狮子座'],
            ['23' => '处女座'],
            ['23' => '天秤座'],
            ['24' => '天蝎座'],
            ['22' => '射手座'],
            ['22' => '摩羯座'],
        ];
        [$sign_start, $sign_name] = reset($signs[(int)$month - 1]);
        if ($day < $sign_start) {
            [$sign_start, $sign_name] = reset($signs[($month - 2 < 0) ? $month = 11 : --$month]);
        }

        return $sign_name;
    }
}

if (!function_exists('is_empty')) {

    /**
     * 判断是否为空
     *
     * @param $value 参数值
     * @return bool 返回结果true或false
     *
     * @author 牧羊人
     *
     * @date 2019/6/5
     */
    function is_empty($value)
    {
        // 判断是否存在该值
        if (!isset($value)) {
            return true;
        }

        // 判断是否为empty
        if (empty($value)) {
            return true;
        }

        // 判断是否为null
        if ($value === null) {
            return true;
        }

        // 判断是否为空字符串
        if (trim($value) === '') {
            return true;
        }

        // 默认返回false
        return false;
    }
}

// TODO 转换

if (!function_exists('strip_html_tags')) {

    /**
     * 去除HTML标签、图像等 仅保留文本
     *
     * @param string $str 字符串
     * @param int $length 长度
     * @return string 返回结果
     */
    function strip_html_tags($str, $length = 0)
    {
        // 把一些预定义的 HTML 实体转换为字符
        $str = htmlspecialchars_decode($str);
        // 将空格替换成空
        $str = str_replace('&nbsp;', '', $str);
        // 函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
        $str = strip_tags($str);
        $str = str_replace(["\n", "\r\n", "\r"], ' ', $str);
        $preg = '/<script[\s\S]*?<\/script>/i';
        // 剥离JS代码
        $str = preg_replace($preg, '', $str, -1);
        if ($length == 2) {
            // 返回字符串中的前100字符串长度的字符
            $str = mb_substr($str, 0, $length, 'utf-8');
        }

        return $str;
    }
}

if (!function_exists('sub_str')) {

    /**
     * 字符串截取
     *
     * @param string $str 需要截取的字符串
     * @param int $start 开始位置
     * @param int $length 截取长度
     * @param bool $suffix 截断显示字符
     * @param string $charset 编码格式
     * @return string 返回结果
     */
    function sub_str($str, $start = 0, $length = 10, $suffix = true, $charset = 'utf-8')
    {
        if (function_exists('mb_substr')) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = implode('', array_slice($match[0], $start, $length));
        }
        $omit = mb_strlen($str) >= $length ? '...' : '';

        return $suffix ? $slice . $omit : $slice;
    }

}

if (!function_exists('num2rmb')) {

    /**
     * 数字金额转大写
     *
     * @param float $num 金额
     * @return string 返回大写金额
     */
    function num2rmb($num): string
    {
        $c1 = '零壹贰叁肆伍陆柒捌玖';
        $c2 = '分角元拾佰仟万拾佰仟亿';
        $num = round($num, 2);
        $num = $num * 100;
        if (strlen($num) > 10) {
            return 'oh,sorry,the number is too long!';
        }
        $i = 0;
        $c = '';
        while (1) {
            if ($i == 0) {
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }
            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }
            $i = $i + 1;
            $num = $num / 10;
            $num = (int)$num;
            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            $m = substr($c, $j, 6);
            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 3;
        }
        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        } // if there is a '0' on the end , chop it out

        return $c . '整';
    }
}

if (!function_exists('to_camel')) {

    /**
     * 数组Key下划线转驼峰(首字母小写)
     *
     * @param array $item 数组
     * @return array|false
     */
    function to_camel(array $item)
    {
        return array_combine(array_map(function ($key) {
            return \Illuminate\Support\Str::camel($key);
        }, array_keys($item)), $item);
    }
}

if (!function_exists('to_snake')) {
    /**
     * 数组Key驼峰转下划线(首字母小写)
     *
     * @param array $item 数组
     */
    function to_snake(array $item)
    {
        return array_combine(array_map(function ($key) {
            return Str::snake($key);
        }, array_keys($item)), $item);
    }
}

if (!function_exists('map_to_snake')) {
    /**
     * 搜索条件驼峰转下划线(首字母小写)
     *
     * @param array $map 搜索条件
     */
    function map_to_snake(array $map): array
    {
        return array_map(function ($item) {
            $item[0] = Str::snake($item[0]);

            return $item;
        }, $map);
    }
}

if (!function_exists('field_to_snake')) {
    /**
     * 查询字段驼峰转下划线(首字母小写)
     *
     * @param array $field 字段条件
     */
    function field_to_snake(array $field): array
    {
        return array_map(function ($item) {
            return Str::snake($item);
        }, $field);
    }
}

if (!function_exists('to_camel_array')) {

    /**
     * 二维数组下划线转驼峰(首字母小写)
     *
     * @param array $data 二维数组
     */
    function to_camel_array(array $data): array
    {
        return array_map(function ($item) {
            return to_camel($item);
        }, $data);
    }
}

if (!function_exists('to_snake_array')) {

    /**
     * 二维数组驼峰转下划线(首字母小写)
     *
     * @param array $data 二维数组
     */
    function to_snake_array(array $data): array
    {
        return array_map(function ($item) {
            return to_snake($item);
        }, $data);
    }
}

if (!function_exists('xml2array')) {

    /**
     * xml转数组
     *
     * @param $xml xml文本
     * @return string
     */
    function xml2array($xml)
    {
        $xml = simplexml_load_string($xml);
        $arr = json_decode(json_encode($xml), true);

        return $arr;
    }
}

if (!function_exists('array2xml')) {
    /**
     * 数组转xml
     *
     * @param $arr 原始数据(数组)
     * @param bool $ignore 是否忽视true或fasle
     * @param int $level 级别(默认：1)
     * @return string 返回结果
     */
    function array2xml($arr, $ignore = true, $level = 1)
    {
        $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
        $space = str_repeat("\t", $level);
        foreach ($arr as $k => $v) {
            if (!is_array($v)) {
                $s .= $space . "<item id=\"$k\">" . ($ignore ? '<![CDATA[' : '') . $v . ($ignore ? ']]>' : '')
                    . "</item>\r\n";
            } else {
                $s .= $space . "<item id=\"$k\">\r\n" . array2xml($v, $ignore, $level + 1) . $space . "</item>\r\n";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);

        return $level == 1 ? $s . '</root>' : $s;
    }
}

if (!function_exists('format_bytes')) {
    /**
     * 将字节转换为可读文本
     *
     * @param int $size 字节大小
     * @param string $delimiter 分隔符
     * @return string 返回结果
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('format_yuan')) {
    /**
     * 以分为单位的金额转换成元
     *
     * @param int $money 金额
     * @return string 返回格式化的金额
     */
    function format_yuan($money = 0)
    {
        if ($money > 0) {
            return number_format($money / 100, 2, '.', '');
        }

        return '0.00';
    }
}

if (!function_exists('format_cent')) {
    /**
     * 以元为单位的金额转化成分
     *
     * @param $money 金额
     * @return string 返回格式化的金额
     */
    function format_cent($money)
    {
        return (string)($money * 100);
    }
}

if (!function_exists('format_mobile')) {
    /**
     * 格式化手机号码
     *
     * @param string $mobile 手机号码
     * @return string 返回结果
     */
    function format_mobile($mobile)
    {
        return substr($mobile, 0, 5) . '****' . substr($mobile, 9, 2);
    }
}

if (!function_exists('format_bank_card')) {
    /**
     * 银行卡格式转换
     *
     * @param string $card_no 银行卡号
     * @param bool $is_format 是否格式化
     * @return string 输出结果
     */
    function format_bank_card($card_no, $is_format = true)
    {
        if ($is_format) {
            // 截取银行卡号前4位
            $prefix = substr($card_no, 0, 4);
            // 截取银行卡号后4位
            $suffix = substr($card_no, -4, 4);

            $format_card_no = $prefix . ' **** **** **** ' . $suffix;
        } else {
            // 4的意思就是每4个为一组
            $arr = str_split($card_no, 4);
            $format_card_no = implode(' ', $arr);
        }

        return $format_card_no;
    }
}

// TODO 文件夹操作

if (!function_exists('mkdirs')) {

    /**
     * 递归创建目录
     *
     * @param string $dir 需要创建的目录路径
     * @param int $mode 权限值
     * @return bool 返回结果true或false
     */
    function mkdirs($dir, $mode = 0777)
    {
        if (is_dir($dir) || mkdir($dir, $mode, true)) {
            return true;
        }
        if (!mkdirs(dirname($dir), $mode)) {
            return false;
        }

        return mkdir($dir, $mode, true);
    }
}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     *
     * @param string $dir 文件夹路径
     * @param bool $rmself 是否删除本身true或false
     * @return bool 返回删除结果
     */
    function rmdirs($dir, $rmself = true)
    {
        if (!is_dir($dir)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $todo = ($file->isDir() ? 'rmdir' : 'unlink');
            $todo($file->getRealPath());
        }
        if ($rmself) {
            @rmdir($dir);
        }

        return true;
    }
}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     *
     * @param string $source 原文件夹路径
     * @param string $dest 目的文件夹路径
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $sent_dir = $dest . '/' . $iterator->getSubPathName();
                if (!is_dir($sent_dir)) {
                    mkdir($sent_dir, 0755, true);
                }
            } else {
                copy($item, $dest . '/' . $iterator->getSubPathName());
            }
        }
    }
}

// TODO 加密 和 解密
if (!function_exists('decrypt')) {
    /**
     * DES解密
     *
     * @param string $str 解密字符串
     * @param string $key 解密KEY
     * @return mixed
     */
    function decrypt($str, $key = 'p@ssw0rd')
    {
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) {
            $str = substr($str, 0, strlen($str) - $pad);
        }

        return unserialize($str);
    }
}

if (!function_exists('encrypt')) {

    /**
     * @param string $str 加密字符串
     * @param string $key 加密KEY
     * @return string
     */
    function encrypt($str, $key = 'p@ssw0rd')
    {
        $prep_code = serialize($str);
        $block = mcrypt_get_block_size('des', 'ecb');
        if (($pad = $block - (strlen($prep_code) % $block)) < $block) {
            $prep_code .= str_repeat(chr($pad), $pad);
        }
        $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB);

        return base64_encode($encrypt);
    }
}
