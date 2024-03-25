<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IDRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // 在这里添加授权逻辑，根据你的需求进行修改
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|gt:0',
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
            'id.required' => 'ID字段是必填的。',
            'id.integer' => 'ID字段必须是一个整数。',
            'id.gt' => 'ID字段必须大于零。',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        return $data;
    }
}
