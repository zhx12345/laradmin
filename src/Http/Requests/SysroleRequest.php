<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 角色 FormRequest
 */
class SysroleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 在这里添加授权逻辑，根据你的需求进行修改
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'label' => 'required|max:50',
            'alias' => 'required|max:50',
            'sort' => 'nullable|integer',
            'remark' => 'nullable|max:255',
            'status' => 'required|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'label.required' => '标签不能为空。',
            'label.max' => '标签长度不能超过50个字符。',
            'alias.required' => '别名不能为空。',
            'alias.max' => '别名长度不能超过50个字符。',
            'sort.integer' => '排序必须是一个整数。',
            'remark.max' => '备注长度不能超过255个字符。',
            'status.required' => '状态不能为空。',
            'status.integer' => '状态必须是一个整数。',
        ];
    }
}
