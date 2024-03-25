<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;


/**
 * 文章-模型
 * @author zhx
 * @since 2024/01/20
 * Class ArticlesModel
 * @package App\Models
 */
class ArticlesModel extends Base
{
    // 设置数据表
    protected $table = "articles";
    // 字段
    protected $fillable = ['id', 'title', 'category_id', 'summary', 'cover_image', 'content', 'is_sell', 'sort', 'status'];


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
