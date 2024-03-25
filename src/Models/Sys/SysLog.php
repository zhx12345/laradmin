<?php

namespace Zhxlan\Laradmin\Models\Sys;

use Zhxlan\Laradmin\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SysLog extends Base
{
    use HasFactory;

    protected $table = 'sys_log';

    protected $fillable = [
        'cip',
        'code',
        'level',
        'time',
        'data',
        'type',
        'user',
        'url',
        'name',
        'msg',
    ];
}
