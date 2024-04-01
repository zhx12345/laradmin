<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Zhxlan\Laradmin\Helpers\ApiResponse;
use Zhxlan\Laradmin\Http\Requests\SysuserRequest;
use Zhxlan\Laradmin\Models\Sys\SysRole;
use Zhxlan\Laradmin\Models\Sys\SysUser;
use Zhxlan\Laradmin\Models\Sys\SysUserRole;



/**
 * 管理员
 * SysuserController class
 */
class SysuserController extends BaseController
{
    public function __construct()
    {
        $this->modelClass = new SysUser();
        $this->requestClass = SysuserRequest::class;
        $this->title = '管理员';
    }

    /**
     * 验证码
     * captcha function
     *
     * @return void
     */
    public function captcha()
    {
        $encipherStr = time();
        return ApiResponse::success(['captcha' => url('admin/api/v1/captchaImg?_=' . $encipherStr), 'time' => $encipherStr ]);
    }

    /**
     * 登录
     * token function
     *
     * @return void
     */
    public function token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required',
            'code' => 'required',
            'autologin' => '',
            'time' => 'required',
        ], [
            'usern.required' => '账号 是必填的。',
            'password.required' => '密码 是必填的。',
            'code.required' => '验证码 是必填的。',
            'time.required' => '操作时间戳 是必填的。',
        ]);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        // 验证验证码
        $result = CommonController::verifyCaptcha($request->input('code'), $request->input('time'));
        if (!$result) {
            return ApiResponse::fail('验证码错误');
        }

        $model = $this->modelClass::query();
        $userData = $model->where('user_name', $request->input('user'))->first();
        if (empty($userData)) {
            return ApiResponse::fail('当前用户名不存在');
        }
        if (password_verify($request->input('password'), $userData->password) === false) {
            return ApiResponse::fail('用户名密码不正确');
        }
        if ($userData->state == 0) {
            return ApiResponse::fail('当前用户已被封停');
        }

        // 更新用户信息
        $user = [
            'token' => get_token($request->input('user')),
            'userInfo' => [],
        ];

        $model->where('id', $userData->id)->update([
            'token' => $user['token'],
            'token_expiration_at' => get_time(24),
        ]);

        $user['userInfo'] = $model->with(['hasRole'])->select(['id', 'avatar', 'user_name', 'name', 'sex', 'mail', 'about', 'birthday', 'last_login_time', 'last_login_ip', 'state', 'created_at'])->first();
        $user['userInfo']['dashboard'] = 0; //设置登录首页显示图
        // 获取用户角名称
        $roleInfo = SysRole::where('id', $user['userInfo']->hasRole->role_id)->first();
        $user['userInfo']['role'] = isset($roleInfo) ? $roleInfo->label : '';
        $this->logs($request, '200', '登录', '登录成功', $user['userInfo']);

        return ApiResponse::success($user);
    }

    /**
     * 密码修改
     * password function
     *
     * @return void
     */
    public function password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confirmNewPassword' => 'required',
            'newPassword' => 'required',
            'userPassword' => 'required',
        ], [
            'confirmNewPassword.required' => '原密码 是必填的。',
            'newPassword.required' => '新密码 是必填的。',
            'userPassword.required' => '确认新密码 是必填的。',
            'newPassword.confirmed' => '新密码和确认新密码不匹配。',
        ]);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        $model = $this->modelClass::query();
        $userData = $model->where('id', $request['global']['user']['id'])->first();

        if (password_verify($request->input('confirmNewPassword'), $userData->password) === false) {
            $this->logs($request, '500', '修改密码', '原密码不正确');

            return ApiResponse::fail('原密码不正确');
        }
        $result = $model->update([
            'password' => password_hash($request->input('userPassword'), PASSWORD_BCRYPT),
        ]);
        if ($result) {
            $this->logs($request, '200', '修改密码', '操作成功');

            return ApiResponse::ok('操作成功');
        } else {
            $this->logs($request, '500', '修改密码', '操作失败');

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
            $models->orWhere('name', 'like', '%' . $requestData['keyword'] . '%');
            $models->orWhere('user_name', 'like', '%' . $requestData['keyword'] . '%');
        }

        // 是否分页
        if ((int)$paging == 1) {
            $list = $models->with(['roles' => function ($query) use ($requestData) {
                if (isset($requestData['role_id'])) {
                    $query->where('role_id', $requestData['role_id']);
                }
            }])->whereHas('roles', function ($query) use ($requestData) {
                if (isset($requestData['role_id'])) {
                    $query->where('role_id', $requestData['role_id']);
                }
            })->whereNull('deleted_at')->orderBy($sort, $sortOrder)->paginate($limit);

            foreach ($list as $key => $value) {
                if (isset($value->roles)) {
                    $group_name = array_column($value->roles->toArray(), 'label');
                    $list->items()[$key]['group_name'] = implode(', ', $group_name);

                    $group_id = array_column($value->roles->toArray(), 'id');
                    $list->items()[$key]['group_id'] = implode(', ', $group_id);
                }
            }

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
        $requestData = $request->all();
        $rules = $this->getValidation();
        $validator = Validator::make($requestData, $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($requestData) {
                // 获取参数
                $data = [
                    'avatar' => $requestData['avatar'],
                    'user_name' => $requestData['user_name'],
                    'password' => Hash::make($requestData['password']),
                    'name' => $requestData['name'],
                    'sex' => isset($requestData['sex']) ? $requestData['sex'] : 0,
                    'mail' => '',
                    'about' => '',
                    'birthday' => '',
                    'state' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                // 保存用户信息
                $models = $this->modelClass::query();
                $id = $models->insertGetId($data);

                // 保存关联角色
                SysUserRole::insert([
                    'user_id' => $id,
                    'role_id' => $requestData['group_id'],
                ]);
            });

            return ApiResponse::ok('创建成功');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ApiResponse::fail('创建失败');
        }
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
        $requestData = $request->all();
        $rules = $this->getValidation();
        $validator = Validator::make($requestData, $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        try {
            DB::transaction(function () use ($requestData, $id) {
                // 获取参数
                $data = [
                    'avatar' => $requestData['avatar'],
                    'user_name' => $requestData['user_name'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                // 保存用户信息
                $models = $this->modelClass::query();
                $models->where('id', $id)->update($data);

                // 保存关联角色
                SysUserRole::where('user_id', $id)->delete();
                SysUserRole::insert([
                    'user_id' => $id,
                    'role_id' => $requestData['group_id'],
                ]);
            });

            return ApiResponse::ok('创建成功');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ApiResponse::fail('创建失败');
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
        $result = $models->whereIn('id', explode(',', $id))->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        if ($result) {
            $this->logs($request, '200', '删除', '删除成功');

            return ApiResponse::ok('删除成功');
        } else {
            $this->logs($request, '500', '删除', '删除失败');

            return ApiResponse::fail('删除失败');
        }
    }
}
