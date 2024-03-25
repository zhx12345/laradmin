<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 文章-FormRequest
 * @author zhx
 * @since 2024/01/20
 * Class ArticlesModel
 * @package App\Http\Requests
 */
class ArticlesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => '',
            'title' => 'required',
            'category_id' => 'required',
            'summary' => 'required',
            'cover_image' => 'required',
            'content' => 'required',
            'is_sell' => 'required',
            'sort' => 'required',
            'status' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        // 验证提示语
        return [
            'id.required' => 'ID 不能为空。',
            'title.required' => '标题 不能为空。',
            'category_id.required' => '分类ID 不能为空。',
            'summary.required' => '摘要 不能为空。',
            'cover_image.required' => '封面图片 不能为空。',
            'content.required' => '内容 不能为空。',
            'is_sell.required' => '特色文章 不能为空。',
            'sort.required' => '顺序 不能为空。',
            'status.required' => '状态，草稿或已发布，默认为草稿 不能为空。',
        ];
    }
}
