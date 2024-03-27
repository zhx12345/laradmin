<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use App\Http\Requests\SysmenuRequest;
use App\Models\Sys\SysMenu;
use App\Models\Sys\SysMenuApi;
use App\Models\Sys\SysMenuMeta;
use App\Models\Sys\SysRoleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\Config;
use Zhxlan\Laradmin\Helpers\ApiResponse;
use Zhxlan\Laradmin\Helpers\Tree;

/**
 * 权限管理
 * SysmenuController class
 */
class SysmenuController extends BaseController
{


    private $sysMenu;

    public function __construct()
    {
        $this->modelClass = new SysMenu();
        $this->requestClass = SysmenuRequest::class;
        $this->title = '角色';
        $this->initialize();

        $this->sysMenu = [
            'id' => 10000000,
            'parent_id' => 0,
            "name" => "develop",
            "path" => "/develop",
            "redirect" => "",
            "component" => "",
            "active" => 0,
            "sort" => 10,
            "meta" => [
                "id" => 10000000,
                "menu_id" => 10000000,
                "color" => "",
                "hidden" => 0,
                "hidden_breadcrumb" => 0,
                "icon" => "el-icon-operation",
                "title" => "开发",
                "type" => "menu",
                "affix" => 0,
                "fullpage" => 0,
            ],
            'children' => [
                [
                    'id' => 10000001,
                    'parent_id' => 0,
                    "name" => "generate",
                    "path" => "/develop/generate",
                    "redirect" => "",
                    "component" => "develop/generate",
                    "active" => 0,
                    "sort" => 10,
                    "meta" => [
                        "id" => 10000001,
                        "menu_id" => 10000001,
                        "color" => "",
                        "hidden" => 0,
                        "hidden_breadcrumb" => 0,
                        "icon" => "el-icon-operation",
                        "title" => "代码生成器",
                        "type" => "menu",
                        "affix" => 0,
                        "fullpage" => 0,
                    ]
                ],
                [
                    'id' => 10000002,
                    'parent_id' => 0,
                    "name" => "about",
                    "path" => "/develop/about",
                    "redirect" => "",
                    "component" => "develop/about",
                    "active" => 0,
                    "sort" => 10,
                    "meta" => [
                        "id" => 10000002,
                        "menu_id" => 10000002,
                        "color" => "",
                        "hidden" => 0,
                        "hidden_breadcrumb" => 0,
                        "icon" => "el-icon-warning",
                        "title" => "VUE",
                        "type" => "menu",
                        "affix" => 0,
                        "fullpage" => 0,
                    ],
                ],
                [
                    'id' => 10000003,
                    'parent_id' => 0,
                    "name" => "VUE3",
                    "path" => "https://v3.cn.vuejs.org/",
                    "redirect" => "",
                    "component" => "",
                    "active" => 0,
                    "sort" => 10,
                    "meta" => [
                        "id" => 10000003,
                        "menu_id" => 10000003,
                        "color" => "",
                        "hidden" => 0,
                        "hidden_breadcrumb" => 0,
                        "icon" => "el-icon-warning",
                        "title" => "VUE3",
                        "type" => "iframe",
                        "affix" => 0,
                        "fullpage" => 0,
                    ],
                ],
                [
                    'id' => 10000003,
                    'parent_id' => 0,
                    "name" => "element-plus",
                    "path" => "https://element-plus.gitee.io/",
                    "redirect" => "",
                    "component" => "",
                    "active" => 0,
                    "sort" => 10,
                    "meta" => [
                        "id" => 10000003,
                        "menu_id" => 10000003,
                        "color" => "",
                        "hidden" => 0,
                        "hidden_breadcrumb" => 0,
                        "icon" => "el-icon-warning",
                        "title" => "element-plus",
                        "type" => "iframe",
                        "affix" => 0,
                        "fullpage" => 0,
                    ],
                ],
                [
                    'id' => 10000003,
                    'parent_id' => 0,
                    "name" => "scui文档",
                    "path" => "https://lolicode.gitee.io/scui-doc",
                    "redirect" => "",
                    "component" => "",
                    "active" => 0,
                    "sort" => 10,
                    "meta" => [
                        "id" => 10000003,
                        "menu_id" => 10000003,
                        "color" => "",
                        "hidden" => 0,
                        "hidden_breadcrumb" => 0,
                        "icon" => "el-icon-warning",
                        "title" => "scui文档",
                        "type" => "iframe",
                        "affix" => 0,
                        "fullpage" => 0,
                    ],
                ]



            ]

        ];
    }

    /**
     * 当前用户的菜单
     * mySysMenu function
     *
     * @return void
     */
    public function myMenu(Request $request)
    {
        if ($request['global']['user']['user_name'] == Config('laradmin.super_username')) {
            //超级管理员不验证权限
            $menuList = $this->modelClass::orderBy('sort', 'desc')->get()->toArray();
        } else {
            $menuList = DB::table('sys_menu as m')
                ->select('m.*')
                ->whereNull('m.deleted_at')
                ->whereExists(function ($query) use ($request) {
                    $query->select(DB::raw(1))
                        ->from('sys_role_menu as rm')
                        ->join('sys_role as r', 'r.id', '=', 'rm.role_id')
                        ->join('sys_user_role as ur', 'ur.role_id', '=', 'r.id')
                        ->whereNull('r.deleted_at')
                        ->where('ur.user_id', $request['global']['user']['id'])
                        ->where('rm.menu_id', 'm.id');
                })
                ->orderBy('m.sort', 'desc')
                ->get()
                ->toArray();
        }
        //没有菜单则返回空
        if (empty($menuList)) {
            return ApiResponse::success([]);
        }

        $menuMetaList = SysMenuMeta::whereIn('menu_id', array_column($menuList, 'id'))->get()->toArray();
        $data = array_map(function ($item) use ($menuMetaList) {
            foreach ($menuMetaList as $vo) {
                if ($item['id'] == $vo['menu_id']) {
                    $item['meta'] = $vo;
                }
            }
            return $item;
        }, $menuList);

        Tree::init($data, 'parent_id', 'id', 'name');
        $menu = Tree::get_childall_data();

        if(env('APP_GENERATE'))
            array_push($menu, $this->sysMenu);

        return ApiResponse::success([
            'menu' => $menu,
            'permissions' => [],
            'dashboardGrid' => ['welcome', 'ver', 'time', 'progress', 'echarts', 'about'],
        ]);
    }

    /**
     * 获取选中
     * index function
     *
     * @return void
     */
    public function check(Request $request, $role)
    {
        $requestData = $request->all();
        $list = SysRoleMenu::where('role_id', $role)->select('menu_id')->get();
        if ($list) {
            return ApiResponse::success(array_column($list->toArray(), 'menu_id'));
        } else {
            return ApiResponse::success([]);
        }
    }

    /**
     * 列表页
     * index function
     *
     * @return void
     */
    public function index(Request $request)
    {
        $requestData = $request->all();
        //获取菜单列表
        [$sort, $sortOrder] = [
            isset($requestData['sort']) ? $requestData['sort'] : 'sort',
            isset($requestData['sortOrder']) ? $requestData['sortOrder'] : 'desc',
        ];
        $models = $this->modelClass::query();
        if (isset($requestData['name'])) {
            $models->where('name', 'like', '%' . $requestData['name'] . '%');
        }
        $menuList = $models->orderBy($sort, $sortOrder)->get();

        //没有菜单则返回空
        if ($menuList->isEmpty()) {
            return ApiResponse::success([]);
        }

        $menuIds = array_column($menuList->toArray(), 'id');
        $menuMetaList = SysMenuMeta::whereIn('menu_id', $menuIds)->get()->toArray();
        $menuApiList = SysMenuApi::whereIn('menu_id', $menuIds)->get()->toArray();
        $data = array_map(function ($item) use ($menuMetaList, $menuApiList) {
            foreach ($menuMetaList as $vo) {
                if ($item['id'] == $vo['menu_id']) {
                    $item['meta'] = $vo;
                }
            }
            $item['apiList'] = [];
            foreach ($menuApiList as $vo) {
                if ($item['id'] == $vo['menu_id']) {
                    $item['apiList'][] = $vo;
                }
            }

            return $item;
        }, $menuList->toArray());

        Tree::init($data, 'parent_id', 'id', 'name');
        $menu = Tree::get_childall_data();

        return ApiResponse::success($menu);
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
        $validator = Validator::make($request->all(), $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            return ApiResponse::fail($validator->errors()->first());
        }

        try {
            $id = DB::transaction(function () use ($requestData) {
                $metaData = $requestData['meta'];
                unset($requestData['meta']);

                $models = $this->modelClass::query();
                $createdModel = $models->create($requestData);

                // 保存Meta
                $metaData['menu_id'] = $createdModel->id;
                SysMenuMeta::insert($metaData);
                return $createdModel->id;
            });
            return ApiResponse::success($id, '创建成功');
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
        $validator = Validator::make($request->all(), $rules['rules'], $rules['messages']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return ApiResponse::fail($error);
            }
        }

        try {
            DB::transaction(function () use ($requestData) {
                $apiList = $requestData['apiList'];
                $metaData = $requestData['meta'];

                // 更新菜单
                $models = $this->modelClass::query();
                $models->where('id', $requestData['id'])->update([
                    'parent_id' => $requestData['parent_id'],
                    'name' => $requestData['name'],
                    'path' => $requestData['path'],
                    'redirect' => isset($requestData['redirect']) ? $requestData['redirect'] : '',
                    'component' => $requestData['component'],
                    'active' => $requestData['active'],
                    'sort' => isset($requestData['sort']) ? $requestData['sort'] : 10,
                ]);

                // 更新 Meta
                SysMenuMeta::where('menu_id', $requestData['id'])->update([
                    'color' => isset($metaData['color']) ? $metaData['color'] : '',
                    'hidden' => isset($metaData['hidden']) ? $metaData['hidden'] : 0,
                    'hidden_breadcrumb' => isset($metaData['hidden_breadcrumb']) ? $metaData['hidden_breadcrumb'] : 0,
                    'icon' => isset($metaData['icon']) ? $metaData['icon'] : '',
                    'title' => isset($metaData['title']) ? $metaData['title'] : '',
                    'type' => isset($metaData['type']) ? $metaData['type'] : 1,
                    'affix' => isset($metaData['affix']) ? $metaData['affix'] : 0,
                ]);

                //更新Api
                $menu_id = $requestData['id'];
                $insertApiList = [];
                array_map(function ($item) use (&$insertApiList, $menu_id) {
                    if (isset($item['id']) && $item['id'] > 0) {
                        SysMenuApi::where('id', $item['id'])->update($item);
                    } else {
                        $item['menu_id'] = $menu_id;
                        $insertApiList[] = $item;
                    }
                }, $apiList);

                if (!empty($insertApiList)) {
                    SysMenuApi::insert($insertApiList);
                }
            });

            return ApiResponse::ok('编辑成功');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ApiResponse::fail('编辑失败');
        }
    }


}
