<?php

namespace  Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Helpers\Tree;
use Zhxlan\Laradmin\Http\Requests\CityRequest;
use Zhxlan\Laradmin\Models\CityModel;

/**
 * 行政区划管理-控制器
 * @author zhx
 * @since: 2024/02/02
 * Class CityController
 * @package App\Http\Controllers
 */
class CityController extends BaseController
{
    /**
     * 构造函数
     * @param Request $request
     * @since 2024/02/02
     * CityController constructor.
     * @author zhx
     */
    public function __construct()
    {
        $this->modelClass = new CityModel();
        $this->requestClass = CityRequest::class;
        $this->title = '行政区划';
        $this->initialize();
    }

    /**
     * 省市 列表
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
    public function cityTree(Request $request)
    {
        $requestData = $request->all();
        [$sort, $sortOrder] = [
            isset($requestData['sort']) ? $requestData['sort'] : 'sort',
            isset($requestData['sortOrder']) ? $requestData['sortOrder'] : 'desc',
        ];
        $models = $this->modelClass::query();
        if (isset($requestData['name'])) {
            $models->where('name', 'like', '%' . $requestData['name'] . '%');
        }

        $list = $models->orderBy($sort, $sortOrder)->get()->toArray();
        Tree::init($list, 'parent_id', 'id', 'name');
        $list = Tree::get_childall_data();
        return ApiResponse::success($list);
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
        [$sort, $sortOrder] = [
            isset($requestData['sort']) ? $requestData['sort'] : 'sort',
            isset($requestData['sortOrder']) ? $requestData['sortOrder'] : 'desc',
        ];
        $models = $this->modelClass::query();
        if (isset($requestData['name'])) {
            $models->where('name', 'like', '%' . $requestData['name'] . '%');
        }
        $list = $models->orderBy($sort, $sortOrder)->get()->toArray();
        Tree::init($list, 'parent_id', 'id', 'name');
        $list = Tree::get_childall_data();
        return ApiResponse::success($list);
    }

}
