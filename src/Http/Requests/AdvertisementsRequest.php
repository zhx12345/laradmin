<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 广告信息-FormRequest
 * @author zhx
 * @since 2024/01/25
 * Class AdvertisementsModel
 * @package App\Http\Requests
 */
class AdvertisementsRequest extends FormRequest
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
            'image' => 'required',
            'type' => 'required',
            'link' => '',
            'store_page' => '',
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
            'id.required' => 'id 不能为空。',
            'category_id.required' => '广告位Id 不能为空。',
            'title.required' => '广告标题 不能为空。',
            'image.required' => '广告图 不能为空。',
            'type.required' => '广告链接类型 1.页面 2.自定义链接 不能为空。',
            'link.required' => '自定义链接 不能为空。',
            'store_page.required' => '页面 不能为空。',
            'sort.required' => '排序 不能为空。',
            'status.required' => '广告状态 不能为空。',
        ];
    }
}
