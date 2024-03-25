<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 内容分类-FormRequest
 * @author zhx
 * @since 2024/01/22
 * Class CategoriesModel
 * @package App\Http\Requests
 */
class CategoriesRequest extends FormRequest
{
    // 设置数据表
    protected $table = "categories";
    // 字段
    protected $fillable = ['id', 'categories', 'name', 'description', 'is_default'];


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
            'type' => 'required',
            'name' => 'required',
            'description' => '',
            'is_default' => 'required',
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
            'type.required' => '类型 不能为空。',
            'name.required' => '分类名称 不能为空。',
            'description.required' => '分类描述 不能为空。',
            'is_default.required' => '默认分类 不能为空。',
        ];
    }
}
