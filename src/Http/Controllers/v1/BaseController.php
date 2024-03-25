<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as CommonContoller;
use Illuminate\Support\Facades\Validator;
use Zhxlan\Laradmin\Controllers\Controller;
use Zhxlan\Laradmin\Helpers\ApiResponse;
use Zhxlan\Laradmin\Http\Requests\IDRequest;
use Zhxlan\Laradmin\Models\Sys\SysLog;


/**
 * 公共类
 * BaseController class
 */
class BaseController extends CommonContoller
{
    /**
     * model 名称
     * modelClass variable
     *
     * @var [type]
     */
    protected $modelClass;

    /**
     * 验证类
     * requestClass variable
     *
     * @var [type]
     */
    protected $requestClass;

    /**
     * 当前操作的模块
     * title variable
     *
     * @var string
     */
    protected $title = '';

    /**
     * 配置参数
     */
    protected $config;

    /**
     * 初始化
     * @return void
     */
    protected function initialize()
    {
        //别名
        if (!isset($this->config['tableAlias'])) {
            $this->config['tableAlias'] = 'a';
        }
        //别名
        if (!isset($this->config['tableField'])) {
            $this->config['tableField'] = '*';
        }
        //是否分页显示
        if (!isset($this->config['tableLimit'])) {
            $this->config['tableLimit'] = true;
        }
        //是否假删除
        if (!isset($this->config['isDelete'])) {
            $this->config['isDelete'] = true;
        }
        //是否转驼峰
        if (!isset($this->config['isCamel'])) {
            $this->config['isCamel'] = true;
        }
        //int类型自动转Boolean类型（1:true、0:false）
        if (!isset($this->config['booleans'])) {
            $this->config['booleans'] = [];
        }
        //是否插入创建&更新人
        if (!isset($this->config['autoWriteUser'])) {
            $this->config['autoWriteUser'] = true;
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
        if (isset($requestData['name'])) {
            $models->where('name', 'like', '%' . $requestData['name'] . '%');
        }
        if (isset($requestData['title'])) {
            $models->where('title', 'like', '%' . $requestData['title'] . '%');
        }
        if (isset($requestData['content'])) {
            $models->where('content', 'like', '%' . $requestData['content'] . '%');
        }
        if (isset($requestData['status'])) {
            $models->where('status', $requestData['status']);
        }
        if (isset($requestData['keyword'])) {}

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

    /**
     * Show the form for creating a new resource.
     *
     * @desc create： GET /re/create
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * 创建保存
     * store function
     *
     * @desc Store a newly created resource in storage.
     * @desc store： POST /re
     *
     * @return void
     */
    public function store(Request $request)
    {
        $rules = $this->getValidation();
        $validator = Validator::make($request->all(), $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        // 获取参数
        $data = [];
        foreach ($rules['rules'] as $key => $item) {
            $data[$key] = $request->input($key);
        }

        $models = $this->modelClass::query();
        $result = $models->create($data);
        if ($result) {
            $this->logs($request, '200', '创建', '创建成功');
            $info = $models->where('id', $result->id)->first();

            return ApiResponse::success($info, '创建成功');
        } else {
            $this->logs($request, '200', '创建', '创建失败');

            return ApiResponse::fail('创建失败');
        }
    }

    /**
     * 详情
     * show function
     *
     * @desc Display the specified resource.
     * @desc show： GET /re/{re}
     *
     * @param [type] $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $this->requestClass = IDRequest::class;
        $rules = $this->getValidation();

        $validator = Validator::make($request->all(), $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return ApiResponse::fail($error);
            }
        }
        $models = $this->modelClass::query();
        $info = $models->where('id', $id)->first();
        if ($info) {
            return ApiResponse::success($info);
        } else {
            return ApiResponse::fail('未查询到数据');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @desc edit： GET /re/{re}/edit
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * 编辑保存
     * update function
     *
     * @desc Update the specified resource in storage.
     * @desc pdate： PUT /re/{re} 或 PATCH /re/{re}
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $rules = $this->getValidation();
        $validator = Validator::make($request->all(), $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return ApiResponse::fail($error);
            }
        }

        // 获取参数
        $data = [];
        foreach ($rules['rules'] as $key => $item) {
            $data[$key] = $request->input($key);
        }

        $models = $this->modelClass::query();
        $result = $models->where('id', $id)->update($data);
        if ($result) {
            $this->logs($request, '200', '编辑', '编辑成功');

            return ApiResponse::ok('编辑成功');
        } else {
            $this->logs($request, '500', '编辑', '编辑失败');

            return ApiResponse::fail('编辑失败');
        }
    }

    /**
     * 删除数据（删除多个id 传参数ids）
     * destroy function
     *
     * @desc Remove the specified resource from storage.
     * @desc  destroy： DELETE /re/{re}
     *
     * @param [type] $id
     * @return void
     */
    public function destroy(Request $request, $id)
    {
        $requestData = $request->all();
        $models = $this->modelClass::query();
        $result = $models->whereIn('id', explode(',', $id))->delete();

        if ($result) {
            $this->logs($request, '200', '删除', '删除成功');

            return ApiResponse::ok('删除成功');
        } else {
            $this->logs($request, '500', '删除', '删除失败');

            return ApiResponse::fail('删除失败');
        }
    }

    /**
     * 获取请求验证规则
     */
    protected function getValidation(): array
    {
        // 使用请求类中的规则
        $request = new $this->requestClass;

        return [
            'rules' => $request->rules(),
            'messages' => $request->messages(),
        ];
    }

    /**
     * 记录操作记录
     * logs function
     *
     * @param [type] $request
     * @param [type] $code
     * @param [type] $msg
     * @return void
     */
    protected function logs($request, $code, $name, $msg)
    {
        $requestAll = $request->except('password');
        $data = [
            'cip' => Request()->ip(),
            'code' => $code,
            'level' => $code == 200 ? 'success' : 'error',
            'time' => date('Y-m-d H:i:s'),
            'data' => json_encode($requestAll),
            'type' => $request->method(),
            'user' => '',
            'url' => $request->path(),
            'name' => $this->title . '-' . $name,
            'msg' => $msg,
        ];

        if (isset($requestAll['global']['user']['id'])) {
            $data['user'] = $requestAll['global']['user']['id'];
        }

        unset($requestAll['global']);
        $result = SysLog::create($data);

        return $result ? true : false;
    }
}
