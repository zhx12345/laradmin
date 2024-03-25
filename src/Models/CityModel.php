<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 行政区划-模型
 * @author zhx
 * @since 2024/02/02
 * Class CityModel
 * @package App\Models
 */
class CityModel extends Base
{
    // 设置数据表
    protected $table = "city";
    // 字段
    protected $fillable = ['id', 'parent_id', 'level', 'name', 'citycode', 'adcode', 'lng', 'lat', 'sort'];

}
