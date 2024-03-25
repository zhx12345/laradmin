<?php

namespace Zhxlan\Laradmin\Http\Controllers;



use Zhxlan\Laradmin\Helpers\ApiResponse;
use Zhxlan\Laradmin\Models\Sys\SysUser;

class IndexController
{

    public function index(){

//        return  ApiResponse::success([1,2,3,4,5]);
        $list = SysUser::paginate();
        return  ApiResponse::created();
    }
}
