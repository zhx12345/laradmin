<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 管理员
 * SysuserRequest class
 */
class SysuserRequest extends FormRequest
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
            'id' => request('id') ? 'integer|gt:0' : '', // 如果存在 id 参数，则添加验证规则，否则为空字符串
            'avatar' => 'string|max:255',
            'user_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sys_user', 'user_name')->ignore(request('id')),
            ],
            'name' => 'required|string|max:255',
            'sex' => 'integer',
            'birthday' => '',
            'about' => '',
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
            'id.integer' => 'ID字段必须是一个整数。',
            'id.gt' => 'ID必须一个整数。',
            'avatar.string' => '头像必须是一个字符串。',
            'avatar.max' => '头像长度不能超过255个字符。',
            'user_name.required' => '用户名是必填项。',
            'user_name.string' => '用户名必须是一个字符串。',
            'user_name.max' => '用户名长度不能超过255个字符。',
            'user_name.unique' => '用户名已存在，请选择其他用户名。',
            'name.required' => '姓名是必填项。',
            'name.string' => '姓名必须是一个字符串。',
            'name.max' => '姓名长度不能超过255个字符。',
            'sex.integer' => '性别必须是一个整数。',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        return $data;
    }
}
