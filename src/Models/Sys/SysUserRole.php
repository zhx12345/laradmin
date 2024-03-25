<?php

namespace Zhxlan\Laradmin\Models\Sys;

use Zhxlan\Laradmin\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SysUserRole extends Base
{
    use HasFactory;

    protected $table = 'sys_user_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'role_id',
    ];
}
