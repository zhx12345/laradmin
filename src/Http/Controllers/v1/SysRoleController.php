<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Http\Requests\SysroleRequest;
use Zhxlan\Laradmin\Models\Sys\SysRole;
use Zhxlan\Laradmin\Models\Sys\SysRoleMenu;

/**
 * 角色管理
 * SysRoleController class
 */
class SysRoleController extends BaseController
{
    public function __construct()
    {
        $this->modelClass = new SysRole();
        $this->requestClass = SysroleRequest::class;
        $this->title = '角色';
        $this->initialize();
    }

    /**
     * 菜单关联角色
     *
     * @param $role
     * @param $menu
     * @return void
     */
    public function sysRoleAsMenu(Request $request)
    {
        $requestData = $request->all(['role', 'menu']);
        $validator = Validator::make($requestData, [
            'role' => 'required',
            'menu' => 'required',
        ], [
            'role.required' => '角色ID 是必填的。',
            'menu.required' => '权限 是必填的。',
        ]);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($requestData) {
                // 清空数据
                SysRoleMenu::where('role_id', $requestData['role'])->delete();
                // 保存
                $menuId = explode(',', $requestData['menu']);
                $params = [];
                foreach ($menuId as $item) {
                    $params[] = [
                        'role_id' => $requestData['role'],
                        'menu_id' => $item,
                    ];
                }
                SysRoleMenu::insert($params);
            });

            return ApiResponse::ok('操作成功');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ApiResponse::fail('操作失败');
        }
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
            $models->orWhere('label', 'like', '%' . $requestData['keyword'] . '%');
            $models->orWhere('alias', 'like', '%' . $requestData['keyword'] . '%');
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
