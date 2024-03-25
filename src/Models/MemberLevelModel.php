<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 会员等级-模型
 * @author zhx
 * @since 2024/02/06
 * Class MemberLevelModel
 * @package App\Models
 */
class MemberLevelModel extends Base
{
    // 设置数据表
    protected $table = "member_level";
    // 字段
    protected $fillable = ['id', 'level', 'title', 'value', 'equity', 'gift'];

}
