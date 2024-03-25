<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 通知-FormRequest
 * @author zhx
 * @since 2024/01/25
 * Class NoticeModel
 * @package App\Http\Requests
 */
class NoticeRequest extends FormRequest
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
            'category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
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
            'category_id.required' => '分类ID 不能为空。',
            'title.required' => '通知标题 不能为空。',
            'content.required' => '通知内容 不能为空。',
        ];
    }
}
