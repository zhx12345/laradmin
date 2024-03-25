<?php

namespace Zhxlan\Laradmin\Models\Sys;

use Zhxlan\Laradmin\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SysMenuMeta extends Base
{
    use HasFactory;

    protected $table = 'sys_menu_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'menu_id',
        'color',
        'hidden',
        'hidden_breadcrumb',
        'icon',
        'title',
        'type',
        'affix',
    ];
}
