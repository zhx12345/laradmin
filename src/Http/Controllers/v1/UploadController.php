<?php

namespace Zhxlan\Laradmin\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Zhxlan\Laradmin\Helpers\ApiResponse;

/**
 * 上传文件
 * SysuserController class
 */
class UploadController extends BaseController
{
    /**
     * 上传文件
     * index function
     *
     * @return void
     */
    public function index(Request $request)
    {
        // 处理多文件上传逻辑
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            // 获取上传文件的原始文件名
            $filename = $file->getClientOriginalName();
            $filename = md5($filename.time()).'.png';
            // 在public磁盘中存储文件
            $path = $file->storeAs('uploads', $filename, 'public');
            // 获取文件的完整 URL
            $url = Storage::disk('public')->url($path);

            return ApiResponse::success([
                'id' => $filename,
                'src' => $url,
                'fileName' => $filename,
            ]);
        } else {
            return ApiResponse::fail('请选择上传的文件');
        }
    }
}
