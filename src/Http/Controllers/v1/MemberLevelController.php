<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Zhxlan\Laradmin\Controllers\v1\Request;
use Zhxlan\Laradmin\Http\Requests\MemberLevelRequest;
use Zhxlan\Laradmin\Models\MemberLevelModel;

/**
 * 会员等级管理-控制器
 * @author zhx
 * @since: 2024/02/06
 * Class MemberLevelController
 * @package App\Http\Controllers
 */
class MemberLevelController extends BaseController
{
    /**
     * 构造函数
     * @param Request $request
     * @since 2024/02/06
     * MemberLevelController constructor.
     * @author zhx
     */
    public function __construct()
    {
        $this->modelClass = new MemberLevelModel();
        $this->requestClass = MemberLevelRequest::class;
        $this->title = '会员等级';
        $this->initialize();
    }

}
