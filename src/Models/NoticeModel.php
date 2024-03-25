<?php

namespace Zhxlan\Laradmin\Models;

use Zhxlan\Laradmin\Models\Base;

/**
 * 通知-模型
 * @author zhx
 * @since 2024/01/25
 * Class NoticeModel
 * @package App\Models
 */
class NoticeModel extends Base
{
    // 设置数据表
    protected $table = "notice";
    // 字段
    protected $fillable = ['category_id', 'title', 'content'];

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
