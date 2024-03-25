<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 协议-FormRequest
 * @author zhx
 * @since 2024/02/18
 * Class AgreementModel
 * @package App\Http\Requests
 */
class AgreementRequest extends FormRequest
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
            'id' => 'required',
            'type' => 'required',
            'content' => '',
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
            'id.required' => 'Id 不能为空。',
            'type.required' => '协议类别 不能为空。',
            'content.required' => '协议内容 不能为空。',
        ];
    }
}
