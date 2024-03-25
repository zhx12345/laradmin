<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Http\Requests\AgreementRequest;
use Zhxlan\Laradmin\Models\AgreementModel;

/**
 * 协议管理-控制器
 * @author zhx
 * @since: 2024/02/18
 * Class AgreementController
 * @package App\Http\Controllers
 */
class AgreementController extends BaseController
{
    /**
     * 构造函数
     * @param Request $request
     * @since 2024/02/18
     * AgreementController constructor.
     * @author zhx
     */
    public function __construct()
    {
        $this->modelClass = new AgreementModel();
        $this->requestClass = AgreementRequest::class;
        $this->title = '协议';
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
        [$sort, $sortOrder] = [
            isset($requestData['sort']) ? $requestData['sort'] : 'id',
            isset($requestData['sortOrder']) ? $requestData['sortOrder'] : 'desc',
        ];
        $models = $this->modelClass::query();
        $list = $models->orderBy($sort, $sortOrder)->get();

        $returnData = [];
        foreach ($list as $item) {
            if ($item['type'] == 1) {
                $returnData['type1'] = $item;
            }
            if ($item['type'] == 2) {
                $returnData['type2'] = $item;
            }
            if ($item['type'] == 3) {
                $returnData['type3'] = $item;
            }
        }

        return ApiResponse::success($returnData);
    }


}
