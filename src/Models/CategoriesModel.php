<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 内容分类-模型
 * @author zhx
 * @since 2024/01/22
 * Class CategoriesModel
 * @package App\Models
 */
class CategoriesModel extends Base
{
    // 设置数据表
    protected $table = "categories";
    // 字段
    protected $fillable = ['id', 'type', 'name', 'description', 'is_default'];

}
