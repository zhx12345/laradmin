<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 会员等级-FormRequest
 * @author zhx
 * @since 2024/02/06
 * Class MemberModel
 * @package App\Http\Requests
 */
class MemberLevelRequest extends FormRequest
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
            'level' => 'required',
            'title' => 'required',
            'value' => 'required',
            'equity' => '',
            'gift' => '',
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
            'level.required' => '会员等级 不能为空。',
            'title.required' => '等级名称 不能为空。',
            'value.required' => '成长值 不能为空。',
            'equity.required' => '等级权益 不能为空。',
            'gift.required' => '等级礼包 不能为空。',
        ];
    }
}
