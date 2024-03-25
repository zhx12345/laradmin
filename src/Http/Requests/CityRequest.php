<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 行政区划-FormRequest
 * @author zhx
 * @since 2024/02/02
 * Class CityModel
 * @package App\Http\Requests
 */
class CityRequest extends FormRequest
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
            'parent_id' => 'required',
            'level' => 'required',
            'name' => 'required',
            'citycode' => 'required',
            'adcode' => '',
            'lng' => '',
            'lat' => '',
            'sort' => 'required',
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
            'id.required' => '编号 不能为空。',
            'parent_id.required' => '父级编号 不能为空。',
            'level.required' => '城市级别 不能为空。',
            'name.required' => '城市名称 不能为空。',
            'citycode.required' => '城市编号（区号） 不能为空。',
            'adcode.required' => '地理编号 不能为空。',
            'lng.required' => '城市坐标中心点经度（* 1e6） 不能为空。',
            'lat.required' => '城市坐标中心点纬度（* 1e6） 不能为空。',
            'sort.required' => '排序号 不能为空。',
        ];
    }
}
