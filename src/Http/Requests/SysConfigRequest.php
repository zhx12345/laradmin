<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 系统配置 FormRequest
 */
class SysConfigRequest extends FormRequest
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
            'key' => [
                Rule::unique('sys_dic_data', 'key')->ignore(request('id')),
            ],
            'value' => '',
            'category' => '',
            'title' => '',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'key.required' => '键值是必填项。',
            'key.max' => '键值不能超过50个字符。',
            'key.unique' => '键值已存在。',
            'value.required' => '值是必填项。',
            'category.required' => '分类是必填项。',
            'title.integer' => '标题是必填项。',
        ];
    }
}
