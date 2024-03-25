<?php

namespace Zhxlan\Laradmin\Models\Sys;

use Zhxlan\Laradmin\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SysMenu extends Base
{
    use HasFactory;

    protected $table = 'sys_menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
        'path',
        'redirect',
        'component',
        'active',
        'sort',
    ];
}
