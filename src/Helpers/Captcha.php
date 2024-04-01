<?php

namespace Zhxlan\Laradmin\Helpers;

//验证码类
class Captcha
{
    private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789'; //随机因子
    private $code;//验证码

    /**
     * 验证码长度
     * @var int
     */
    private $codelen = 4;

    /**
     * 宽度
     * @var int
     */
    private $width = 130;

    /**
     * 高度
     * @var int
     */
    private $height = 50;

    /**
     * 图形资源句柄
     * @var
     */
    private $img;

    /**
     * 字体
     * @var string
     */
    private $font;

    /**
     * 字体大小
     * @var int
     */
    private $fontsize = 20;

    /**
     * 指定字体颜色
     * @var
     */
    private $fontcolor;

    /**
     * 线条数量
     * @var int
     */
    private $line = 6;

    /**
     * 雪花数量
     * @var int
     */
    private $snowflake = 100;


    /**
     * 构造方法初始化
     */
    public function __construct()
    {
        $this->font = dirname(__FILE__) . '/Font/zhankukuhei.ttf';//注意字体路径要写对，否则显示不了图片
    }

    /**
     * 生成随机码
     * @return void
     */
    private function createCode()
    {
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0, $_len)];
        }
    }

    /**
     * 生成背景
     * @return void
     */
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 生成文字
     * @return void
     */
    private function createFont()
    {
        $_x = $this->width / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i]);
        }
    }

    /**
     * 生成线条、雪花
     * @return void
     */
    private function createLine()
    {
        //线条
        for ($i = 0; $i < $this->line; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < $this->snowflake; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    /**
     * 输出
     * @return void
     */
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * 对外生成
     * @return void
     */
    public function doimg()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    /**
     * 获取验证码
     * @return string
     */
    public function getCode()
    {
        return strtolower($this->code);
    }
}
