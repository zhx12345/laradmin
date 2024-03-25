<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 会员-模型
 * @author zhx
 * @since 2024/02/06
 * Class MemberModel
 * @package App\Models
 */
class MemberModel extends Base
{
    // 设置数据表
    protected $table = "member";
    // 字段
    protected $fillable = ['id', 'username', 'password', 'phone', 'membership_code', 'nickname', 'realname', 'birthday', 'gender', 'source_channel', 'address', 'growth_value', 'integral_value', 'balance_value', 'last_accessed_times', 'last_accessed_ip'];

}
