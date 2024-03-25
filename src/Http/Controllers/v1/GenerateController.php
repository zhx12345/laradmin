<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhxlan\Laradmin\Helpers\ApiResponse;

use Zhxlan\Laradmin\Controllers\v1\数据表名;
use function Zhxlan\Laradmin\Controllers\v1\getter;
use function Zhxlan\Laradmin\Controllers\v1\is_empty;

/**
 * 代码生成器 - 控制器
 */
class GenerateController extends BaseController
{
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
        // 请求参数
        $param = $request->all();
        // 查询SQL
        $sql = 'SHOW TABLE STATUS WHERE 1';
        // 表名称
        $name = getter($param, 'name');
        if ($name) {
            $sql .= " AND NAME like \"%{$name}%\" ";
        }
        // 表描述
        $comment = getter($param, 'comment');
        if ($comment) {
            $sql .= " AND COMMENT like \"%{$comment}%\" ";
        }
        $list = DB::select($sql);
        $list = json_decode(json_encode($list), true);
        $list = array_map('array_change_key_case', $list);

        return ApiResponse::success([
            'total' => count($list), // 总页数
            'page' => 1, // 当前页
            'pageSize' => 1, // 总页数
            'rows' => $list, // 数据
        ]);
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
        $requestData = $request->all(['name', 'type', 'comment']);
        if (is_empty($requestData['name'])) {
            return ApiResponse::fail('请选择数据表');
        }

        $tableName = $this->getTableName($requestData['name']);
        $moduleName = $this->getModuleName($tableName);
        $comment = $this->getComment($requestData['comment']);
        $build = $this->getColumnList($requestData['name']);

        // 判断类型生成对应文件
        if ($requestData['type'] == 'model') {
            $this->generateModel($tableName, $moduleName, $comment, $build);
        } else if ($requestData['type'] == 'controller') {
            $this->generateController($tableName, $moduleName, $comment, $build);
        } else if ($requestData['type'] == 'formRequest') {
            $this->generateFormRequest($tableName, $moduleName, $comment, $build);
        } else if ($requestData['type'] == 'vue-page') {
            $this->generateVuePage($tableName, $moduleName, $comment, $build);
        } else if ($requestData['type'] == 'all') {
            $this->generateModel($tableName, $moduleName, $comment, $build);
            $this->generateController($tableName, $moduleName, $comment, $build);
            $this->generateFormRequest($tableName, $moduleName, $comment, $build);
        }

        return ApiResponse::ok(sprintf('本次共生成【%d】个模块', 1));
    }




    /**
     * 生成模型
     * @param string $tableName 数据表名称
     * @param string $moduleName 模型名称
     * @param string $comment 数据表注释
     * @param array $build 数据表结构
     * @param string $author 作者
     * @return mixed|true
     */
    public function generateModel(string $tableName, string $moduleName, string $comment, array $build, string $author = 'zhx')
    {
        $moduleImage = false;
        foreach ($build as &$val) {
            // 图片字段处理
            if (strpos($val['columnName'], "cover") !== false ||
                strpos($val['columnName'], "avatar") !== false ||
                strpos($val['columnName'], "image") !== false ||
                strpos($val['columnName'], "logo") !== false ||
                strpos($val['columnName'], "pic") !== false) {
                $val['columnImage'] = true;
                $moduleImage = true;
            }
        }
        // 参数
        $param = [
            'author' => $author,
            'since' => date('Y/m/d', time()),
            'moduleName' => $moduleName,
            'moduleTitle' => $comment,
            'tableName' => $tableName,
            'columnList' => $build,
            'moduleImage' => $moduleImage,
            'columnName' => array_column($build, 'columnName')
        ];

        // 存储目录
        $FILE_PATH = app_path() . '/Models';
        if (!is_dir($FILE_PATH)) {
            // 创建目录并赋予权限
            mkdir($FILE_PATH, 0777, true);
        }
        // 文件名
        $filename = $FILE_PATH . "/{$moduleName}Model.php";
        // 拆解参数
        extract($param);
        // 开启缓冲区
        ob_start();
        // 引入模板文件
        require(resource_path() . '/views/templates/model.blade.php');
        // 获取缓冲区内容
        $out = ob_get_clean();
        // 打开文件
        $f = fopen($filename, 'w');
        // 写入内容
        fwrite($f, "<?php " . $out);
        // 关闭
        fclose($f);
    }

    /**
     * 生成 formRequest验证类
     * @param string $tableName 数据表名称
     * @param string $moduleName 模型名称
     * @param string $comment 数据表注释
     * @param array $build 数据表结构
     * @param string $author 作者
     * @return void
     */
    public function generateFormRequest(string $tableName, string $moduleName, string $comment, array $build, string $author = 'zhx')
    {
        $moduleImage = false;
        foreach ($build as &$val) {
            // 图片字段处理
            if (strpos($val['columnName'], "cover") !== false ||
                strpos($val['columnName'], "avatar") !== false ||
                strpos($val['columnName'], "image") !== false ||
                strpos($val['columnName'], "logo") !== false ||
                strpos($val['columnName'], "pic") !== false) {
                $val['columnImage'] = true;
                $moduleImage = true;
            }
        }
        // 参数
        $param = [
            'author' => $author,
            'since' => date('Y/m/d', time()),
            'moduleName' => $moduleName,
            'moduleTitle' => $comment,
            'tableName' => $tableName,
            'columnList' => $build,
            'moduleImage' => $moduleImage,
            'columnName' => array_column($build, 'columnName')
        ];

        // 存储目录
        $FILE_PATH = app_path() . '/Http/Requests';
        if (!is_dir($FILE_PATH)) {
            // 创建目录并赋予权限
            mkdir($FILE_PATH, 0777, true);
        }
        // 文件名
        $filename = $FILE_PATH . "/{$moduleName}Request.php";
        // 拆解参数
        extract($param);
        // 开启缓冲区
        ob_start();
        // 引入模板文件
        require(resource_path() . '/views/templates/formRequest.blade.php');
        // 获取缓冲区内容
        $out = ob_get_clean();
        // 打开文件
        $f = fopen($filename, 'w');
        // 写入内容
        fwrite($f, "<?php " . $out);
        // 关闭
        fclose($f);
    }

    /**
     * 生成 controller 控制器
     * @param string $tableName 数据表名称
     * @param string $moduleName 模型名称
     * @param string $comment 数据表注释
     * @param array $build 数据表结构
     * @param string $author 作者
     * @return void
     */
    public function generateController(string $tableName, string $moduleName, string $comment, array $build, string $author = 'zhx')
    {
        $moduleImage = false;
        foreach ($build as &$val) {
            // 图片字段处理
            if (strpos($val['columnName'], "cover") !== false ||
                strpos($val['columnName'], "avatar") !== false ||
                strpos($val['columnName'], "image") !== false ||
                strpos($val['columnName'], "logo") !== false ||
                strpos($val['columnName'], "pic") !== false) {
                $val['columnImage'] = true;
                $moduleImage = true;
            }
        }
        // 参数
        $param = [
            'author' => $author,
            'since' => date('Y/m/d', time()),
            'moduleName' => $moduleName,
            'moduleTitle' => $comment,
            'tableName' => $tableName,
            'columnList' => $build,
            'moduleImage' => $moduleImage,
            'columnName' => array_column($build, 'columnName')
        ];

        // 存储目录
        $FILE_PATH = app_path() . '/Http/Controllers/Admin';
        if (!is_dir($FILE_PATH)) {
            // 创建目录并赋予权限
            mkdir($FILE_PATH, 0777, true);
        }
        // 文件名
        $filename = $FILE_PATH . "/{$moduleName}Controller.php";
        // 拆解参数
        extract($param);
        // 开启缓冲区
        ob_start();
        // 引入模板文件
        require(resource_path() . '/views/templates/controller.blade.php');
        // 获取缓冲区内容
        $out = ob_get_clean();
        // 打开文件
        $f = fopen($filename, 'w');
        // 写入内容
        fwrite($f, "<?php " . $out);
        // 关闭
        fclose($f);
    }

    /**
     * 生成 vue page 页面
     * @param string $tableName 数据表名称
     * @param string $moduleName 模型名称
     * @param string $comment 数据表注释
     * @param array $build 数据表结构
     * @param string $author 作者
     * @return void
     */
    public function generateVuePage(string $tableName, string $moduleName, string $comment, array $build, string $author = 'zhx')
    {
        $moduleImage = false;
        foreach ($build as &$val) {
            // 图片字段处理
            if (strpos($val['columnName'], "cover") !== false ||
                strpos($val['columnName'], "avatar") !== false ||
                strpos($val['columnName'], "image") !== false ||
                strpos($val['columnName'], "logo") !== false ||
                strpos($val['columnName'], "pic") !== false) {
                $val['columnImage'] = true;
                $moduleImage = true;
            }
        }
        // 参数
        $param = [
            'author' => $author,
            'since' => date('Y/m/d', time()),
            'moduleName' => $moduleName,
            'moduleTitle' => $comment,
            'tableName' => $tableName,
            'columnList' => $build,
            'moduleImage' => $moduleImage,
            'columnName' => array_column($build, 'columnName')
        ];

    }









    /**
     * 获取数据库表名
     * @param string $name
     * @return void
     */
    private function getTableName(string $name){
        // +----------------------------------------------------------------------
        // | 数据表名称
        // +----------------------------------------------------------------------
        return str_replace(env('DB_PREFIX'), null, $name);
    }

    /**
     * 获取数据库表名
     * @param string $name
     * @return void
     */
    private function getModuleName(string $name)
    {
        // +----------------------------------------------------------------------
        // | 模型名称
        // +----------------------------------------------------------------------
        return str_replace(' ', null, ucwords(strtolower(str_replace('_', ' ', $name))));
    }

    /**
     * 获取数据库表注释
     * @param string $comment
     * @return void
     */
    private function getComment(string $comment)
    {
        // +----------------------------------------------------------------------
        // | 数据表注释
        // +----------------------------------------------------------------------

        // 去除表描述中的`表`
        if (strpos($comment, "表") !== false) {
            $comment = str_replace("表", null, $comment);
        }
        // 去除表描述中的`管理`
        if (strpos($comment, "管理") !== false) {
            $comment = str_replace("管理", null, $comment);
        }
        return $comment;
    }

    /**
     * 生成字段列表
     * @param $tableName 数据表名
     * @return array
     * @author 牧羊人
     * @since 2020/11/12
     */
    private function getColumnList($tableName)
    {
        // 获取表列字段信息
        $columnList = DB::select("SELECT COLUMN_NAME,COLUMN_DEFAULT,DATA_TYPE,COLUMN_TYPE,COLUMN_COMMENT FROM information_schema.`COLUMNS` where TABLE_SCHEMA = '" . env('DB_DATABASE') . "' AND TABLE_NAME = '{$tableName}'");
        $columnList = json_decode(json_encode($columnList), true);
        $fields = [];
        if ($columnList) {
            foreach ($columnList as $val) {

                if (array_search($val['COLUMN_NAME'], ['', 'created_at', 'updated_at', 'deleted_at'])) {
                    break;
                }

                $column = [];
                // 列名称
                $column['columnName'] = $val['COLUMN_NAME'];
                // 列默认值
                $column['columnDefault'] = $val['COLUMN_DEFAULT'];
                // 数据类型
                $column['dataType'] = $val['DATA_TYPE'];
                // 列描述
                if (strpos($val['COLUMN_COMMENT'], '：') !== false) {
                    $item = explode("：", $val['COLUMN_COMMENT']);
                    $column['columnComment'] = $item[0];

                    // 拆解字段描述
                    $param = explode(" ", $item[1]);
                    $columnValue = [];
                    $columnValueList = [];
                    $typeList = ["", "success", "warning", "danger", "info", "", "success", "warning", "danger", "info", "", "success", "warning", "danger", "info"];
                    foreach ($param as $ko => $vo) {
                        // 键值
                        $key = preg_replace('/[^0-9]/', '', $vo);
                        // 键值内容
                        $value = str_replace($key, null, $vo);
//                        $columnValue[] = "{$key}={$value}";
                        $columnValue[] = [
                            'value' => $key,
                            'name' => $value,
                            'type' => $typeList[$ko],
                        ];
                        $columnValueList[] = $value;
                    }
                    $column['columnValue'] = $columnValue;//implode(',', $columnValue);
                    if ($val['COLUMN_NAME'] == "status" || substr($val['COLUMN_NAME'], 0, 3) == "is_") {
                        $column['columnSwitch'] = true;
                        $column['columnSwitchValue'] = implode('|', $columnValueList);
                        if ($val['COLUMN_NAME'] == "status") {
                            $column['columnSwitchName'] = "status";
                        } else {
                            $column['columnSwitchName'] = 'set' . str_replace(' ', null, ucwords(strtolower(str_replace('_', ' ', $val['COLUMN_NAME']))));
                        }
                    } else {
                        $column['columnSwitch'] = false;
                    }
                } else {
                    $column['columnComment'] = $val['COLUMN_COMMENT'];
                }
                $fields[] = $column;
            }
        }
        return $fields;
    }

}
