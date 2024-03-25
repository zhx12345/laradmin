<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 广告信息-模型
 * @author zhx
 * @since 2024/01/25
 * Class AdvertisementsModel
 * @package App\Models
 */
class AdvertisementsModel extends Base
{
    // 设置数据表
    protected $table = "advertisements";
    // 字段
    protected $fillable = ['id', 'category_id', 'title', 'image', 'type', 'link', 'store_page', 'sort', 'status'];

    /**
     * 关联角色
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hasCategories()
    {
        return $this->hasOne(CategoriesModel::class, 'id', 'category_id');
    }
}
