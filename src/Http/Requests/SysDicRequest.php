<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 数据字典 FormRequest
 */
class SysDicRequest extends FormRequest
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
            'parent_id' => 'required',
            'key' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sys_dic_data', 'key')->ignore(request('id')),
            ],
            'name' => 'required|max:50',
            'sort' => 'integer',
            'yx' => 'nullable|boolean',
            // Add any additional validation rules for other fields here
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
            'parent_id.required' => '父ID是必填项。',
            'key.required' => '键值是必填项。',
            'key.max' => '键值不能超过50个字符。',
            'key.unique' => '键值已存在。',
            'name.required' => '名称是必填项。',
            'name.max' => '名称不能超过50个字符。',
            'sort.required' => '排序是必填项。',
            'sort.integer' => '排序必须是整数。',
            'yx.boolean' => '是否有效必须是布尔值。',
            // Add any additional error messages for other fields here
        ];
    }
}
