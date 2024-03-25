<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 菜单 FormRequest
 */
class SysmenuRequest extends FormRequest
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
            'id' => $this->route('id') ? 'integer|gt:0' : '', // 如果存在 id 参数，则添加验证规则，否则为空字符串
            'name' => [
                'required',
                'string',
                'max:255',
                //                Rule::unique('sys_menu', 'name')->ignore(request('id')),
            ],
            'parent_id' => '',
            'path' => '',
            'redirect' => '',
            'component' => '',
            'active' => 'integer',
            'sort' => 'integer',
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
            'id.gt' => 'ID字段必须大于零。',
            'name.required' => '名称字段是必填的。',
            'name.unique' => '名称已存在。',
            'name.max' => '名称字段不能超过255个字符。',
            'parent_id.required' => '父级ID字段是必填的。',
            'parent_id.integer' => 'parent_id字段必须是一个整数。',
            'path.string' => '路由地址字段必须是一个字符串。',
            'redirect.string' => '重定向地址字段必须是一个字符串。',
            'component.string' => '视图字段必须是一个字符串。',
            'active.integer' => '菜单高亮字段必须是一个整数。',
            'sort.integer' => '排序字段必须是一个整数。',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        return $data;
    }
}
