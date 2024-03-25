<?php

namespace Zhxlan\Laradmin\Models\Sys;

use Zhxlan\Laradmin\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysRole extends Base
{
    use HasFactory, SoftDeletes;

    protected $table = 'sys_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'alias',
        'sort',
        'remark',
        'status',
    ];
}
