<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Http\Requests\SysConfigRequest;
use Zhxlan\Laradmin\Models\Sys\SysConfig;

/**
 * 配置管理
 * SysConfigController class
 */
class SysConfigController extends BaseController
{
    public function __construct()
    {
        $this->modelClass = new SysConfig();
        $this->requestClass = SysConfigRequest::class;
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
        if (isset($requestData['name'])) {
            $models->where('name', 'like', '%'.$requestData['name'].'%');
        }
        $list = $models->orderBy($sort, $sortOrder)->get();
        $returnData = [];
        foreach ($list as $item) {
            if ($item->type == 1) {
                if ($item->key == 'login') {
                    $returnData['sys'][$item->key] = boolval($item->value);
                } else {
                    $returnData['sys'][$item->key] = $item->value;
                }
            }
            if ($item->type == 2) {
                if ($item->key == 'sms_open') {
                    $returnData['sms'][$item->key] = boolval($item->value);
                } else {
                    $returnData['sms'][$item->key] = $item->value;
                }
            }
            if ($item->type == 3) {
                $returnData['setting'][] = $item;
            }
            if ($item->type == 4) {
                $returnData['setting'][] = $item;
            }
        }

        return ApiResponse::success($returnData);
    }

    /**
     * 编辑 sys配置
     *
     * @return void
     */
    public function editSysConfig(Request $request)
    {
        $requestData = $request->all();
        try {
            DB::transaction(function () use ($requestData) {

                $this->modelClass::where('key', 'copyrightNumber')->update(['value' => $requestData['copyrightNumber']]);
                $this->modelClass::where('key', 'copyright')->update(['value' => $requestData['copyright']]);
                $this->modelClass::where('key', 'passwordRules')->update(['value' => $requestData['passwordRules']]);
                $this->modelClass::where('key', 'logoUrl')->update(['value' => $requestData['logoUrl']]);
                $this->modelClass::where('key', 'name')->update(['value' => $requestData['name']]);
                $this->modelClass::where('key', 'banIp')->update(['value' => $requestData['banIp']]);

            });

            return ApiResponse::ok('编辑成功');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ApiResponse::fail('编辑失败');
        }
    }

    /**
     * 编辑 user配置
     *
     * @return void
     */
    public function editSmsConfig(Request $request)
    {
        $requestData = $request->all();
        try {
            DB::transaction(function () use ($requestData) {
                $this->modelClass::where('key', 'sms_open')->update(['value' => $requestData['sms_open']]);
                $this->modelClass::where('key', 'sms_appKey')->update(['value' => $requestData['sms_appKey']]);
                $this->modelClass::where('key', 'sms_secretKey')->update(['value' => $requestData['sms_secretKey']]);
                $this->modelClass::where('key', 'sms_signature')->update(['value' => $requestData['sms_signature']]);
            });

            return ApiResponse::ok('编辑成功');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ApiResponse::fail('编辑失败');
        }
    }






}
