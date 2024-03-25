<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Http\Requests\MemberRequest;
use Zhxlan\Laradmin\Models\MemberLevelModel;
use Zhxlan\Laradmin\Models\MemberModel;

/**
 * 会员管理-控制器
 * @author zhx
 * @since: 2024/02/06
 * Class MemberController
 * @package App\Http\Controllers
 */
class MemberController extends BaseController
{
    /**
     * 构造函数
     * @param Request $request
     * @since 2024/02/06
     * MemberController constructor.
     * @author zhx
     */
    public function __construct()
    {
        $this->modelClass = new MemberModel();
        $this->requestClass = MemberRequest::class;
        $this->title = '会员';
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

        if (isset($requestData['keyword'])) {
            $models->orWhere('username', 'like', '%' . $requestData['keyword'] . '%');
            $models->orWhere('phone', 'like', '%' . $requestData['keyword'] . '%');
            $models->orWhere('membership_code', 'like', '%' . $requestData['keyword'] . '%');
            $models->orWhere('nickname', 'like', '%' . $requestData['keyword'] . '%');
            $models->orWhere('realname', 'like', '%' . $requestData['keyword'] . '%');
        }
        if (isset($requestData['groupId'])) {
            $levelInfo = MemberLevelModel::where('id', $requestData['groupId'])->first();
            if($levelInfo){
                // 下一条数据
                $nextInfo  = MemberLevelModel::where('id', '>', $requestData['groupId'])->orderBy('id')->first();
                $models->where('growth_value', '>=', $levelInfo->value);
                $models->where('growth_value', '<', $nextInfo->value);
            }
        }

        // 是否分页
        if ((int)$paging == 1) {
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
