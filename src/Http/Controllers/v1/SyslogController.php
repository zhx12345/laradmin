<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Models\Sys\SysLog;

/**
 * 操作日志管理
 * SyslogController class
 */
class SyslogController extends BaseController
{
    public function __construct()
    {
        // 定义 Model 模型
        $this->modelClass = new SysLog();
        $this->title = '操作日志';
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
        $models->where('user', $request['global']['user']['id']);
        // 是否分页
        if ((int) $paging == 1) {
            $list = $models->orderBy($sort, $sortOrder)->paginate($limit);

            return ApiResponse::success($list);
        } else {
            $list = $models->orderBy($sort, $sortOrder)->get();

            return ApiResponse::success($list);
        }
    }
}
