<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 协议-模型
 * @author zhx
 * @since 2024/02/18
 * Class AgreementModel
 * @package App\Models
 */
class AgreementModel extends Base
{
    // 设置数据表
    protected $table = "agreement";
    // 字段
    protected $fillable = ['id', 'type', 'content'];

}
