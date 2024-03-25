<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;
use Zhxlan\Laradmin\Helpers\Tree;
use Zhxlan\Laradmin\Http\Requests\SysDicTypeRequest;
use Zhxlan\Laradmin\Models\Sys\SysDicType;

/**
 * 数据字典分类管理
 * SysDicTypeController class
 */
class SysDicTypeController extends BaseController
{
    public function __construct()
    {
        $this->modelClass = new SysDicType();
        $this->requestClass = SysDicTypeRequest::class;
        $this->title = '数据字典分类';
        $this->initialize();
    }

    /**
     *  获取字典类型树
     *
     * @return void
     */
    public function tree(Request $request)
    {
        $list = $this->modelClass::whereNull('deleted_at')->get();
        Tree::init($list, 'parent_id', 'id', 'code');

        return ApiResponse::success(Tree::get_childall_data());
    }
}
