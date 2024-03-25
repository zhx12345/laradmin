<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Http\Requests\SysDicRequest;
use Zhxlan\Laradmin\Models\Sys\SysDicData;

/**
 * 数据字典管理
 * SysDicController class
 */
class SysDicController extends BaseController
{
    public function __construct()
    {
        $this->modelClass = new SysDicData();
        $this->requestClass = SysDicRequest::class;
        $this->title = '数据字典';
        $this->initialize();
    }

    /**
     * 列表
     * index function
     *
     * @desc Display a listing of the resource.
     * @desc index： GET /re
     *
     * @param paging 是否分页，默认true
     * @param limit 显示条数
     * @param page 当前页
     * @param sort 排序参数
     * @param sortOrder 排序方式
     * @return void
     */
    public function index(Request $request)
    {
        $requestData = $request->all();
        [$paging, $limit, $page, $sort, $sortOrder] = [
            isset($requestData['paging']) ? $requestData['paging'] : true,
            isset($requestData['limit']) ? $requestData['limit'] : 10,
            isset($requestData['page']) ? $requestData['page'] : 1,
            isset($requestData['sort']) ? $requestData['sort'] : 'id',
            isset($requestData['sortOrder']) ? $requestData['sortOrder'] : 'desc',
        ];
        $models = $this->modelClass::query();

        if (isset($requestData['parent_id'])) {
            $models->where('parent_id', $requestData['parent_id']);
        }
        // 是否分页
        if ((int) $paging == 1) {
            $list = $models->orderBy($sort, $sortOrder)->paginate($limit);

            return ApiResponse::success([
                'total' => $list->total(), // 总页数
                'page' => $list->currentPage(), // 当前页
                'pageSize' => $list->lastPage(), // 总页数
                'rows' => $list->items(), // 数据
            ]);
        } else {
            $list = $models->orderBy($sort, $sortOrder)->get();

            return ApiResponse::success($list);
        }
    }
}
