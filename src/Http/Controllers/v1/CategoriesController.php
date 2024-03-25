<?php

namespace  Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Http\Requests\CategoriesRequest;
use Zhxlan\Laradmin\Models\CategoriesModel;

/**
 * 内容分类管理-控制器
 * @author zhx
 * @since: 2024/01/22
 * Class CategoriesController
 * @package App\Http\Controllers
 */
class CategoriesController extends BaseController
{
    /**
     * 构造函数
     * @param Request $request
     * @since 2024/01/22
     * CategoriesController constructor.
     * @author zhx
     */
    public function __construct()
    {
        $this->modelClass = new CategoriesModel();
        $this->requestClass = CategoriesRequest::class;
        $this->title = '内容分类';
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
        if (isset($requestData['name'])) {
            $models->where('name', 'like', '%'.$requestData['name'].'%');
        }
        if (isset($requestData['type'])) {
            $models->where('type', $requestData['type']);
        }
        if (isset($requestData['is_default'])) {
            $models->where('is_default', $requestData['is_default']);
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
