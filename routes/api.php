<?php

use Illuminate\Support\Facades\Route;
use \Zhxlan\Laradmin\Http\Controllers\v1 as v1;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * v1 未验证登录信息
 */
Route::prefix('admin/api/v1')->group(function () {
    Route::get('captcha', [v1\SysuserController::class, 'captcha']); // [验证码]
    Route::get('captchaImg', [v1\CommonController::class, 'captchaImg']); // [验证码]
    Route::post('token', [v1\SysuserController::class, 'token']); // [登录]
    Route::post('upload', [v1\UploadController::class, 'index']); // [多文件上传]
    Route::resource('generate', v1\GenerateController::class); // [代码生成器]
});

/**
 * v1 验证登录信息
 */
Route::prefix('admin/api/v1')->middleware(['verify.login'])->group(function () {

    Route::get('sysver', [v1\CommonController::class, 'sysVar']); // [版本]
    Route::post('password', [v1\SysuserController::class, 'password']); // [密码修改]
    Route::get('chackMenu/{role}', [v1\SysmenuController::class, 'check']); // [获取角色权限]
    Route::get('sysRoleAsMenu', [v1\SysRoleController::class, 'sysRoleAsMenu']); // [权限]
    Route::get('myMenu', [v1\SysmenuController::class, 'myMenu']); // [权限]
    Route::get('sysDicTree', [v1\SysDicTypeController::class, 'tree']); // [数据字典分类]
    Route::put('editSysConfig', [v1\SysConfigController::class, 'editSysConfig']); // [系统配置 - sys配置]
    Route::put('editSmsConfig', [v1\SysConfigController::class, 'editSmsConfig']); // [系统配置 - sms配置]
    Route::get('cityTree', [v1\CityController::class, 'cityTree']); // [城市管理 - 省市Tree]

    Route::resource('sysRole', v1\SysRoleController::class); // [角色]
    Route::resource('sysMenu', v1\SysmenuController::class); // [权限菜单]
    Route::resource('sysLog', v1\SyslogController::class); // [操作日志]
    Route::resource('sysDic', v1\SysDicController::class); // [数据字典]
    Route::resource('sysDicType', v1\SysDicTypeController::class); // [数据字典分类]
    Route::resource('city', v1\CityController::class); // [城市管理]
    Route::resource('sysUser', v1\SysuserController::class); // [管理员]
    Route::resource('sysConfig', v1\SysConfigController::class); // [系统配置]
    Route::resource('agreement', v1\AgreementController::class); // [协议管理]
    Route::resource('categories', v1\CategoriesController::class); // [分类管理]
    Route::resource('articles', v1\ArticlesController::class); // [文章管理]
    Route::resource('notices', v1\NoticeController::class); // [通知管理]
    Route::resource('advertisements', v1\AdvertisementsController::class); // [广告管理]
    Route::resource('member', v1\MemberController::class); // [会员管理]
    Route::resource('memberLevel', v1\MemberLevelController::class); // [会员等级管理]

});
