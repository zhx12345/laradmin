<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 数据字典分类 FormRequest
 */
class SysDicTypeRequest extends FormRequest
{
    /**
     * 确定用户是否有权进行此请求。
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 获取适用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'parent_id' => '',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sys_dic_type', 'code')->ignore(request('id')),
            ],
            'name' => 'required|max:50',
            'sort' => '',
            // Add any additional validation rules for other fields here
        ];

        return $rules;
    }

    /**
     * 获取定义的验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'parent_id.required' => '父ID是必填项。',
            'code.required' => '编码是必填项。',
            'code.max' => '编码不能超过50个字符。',
            'code.unique' => '编码必须是唯一的。',
            'name.required' => '名称是必填项。',
            'name.max' => '名称不能超过50个字符。',
            'sort.required' => '排序是必填项。',
            'sort.integer' => '排序必须是整数。',
            // Add any additional error messages for other fields here
        ];
    }
}
