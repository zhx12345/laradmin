<?php

namespace Zhxlan\Laradmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 会员-FormRequest
 * @author zhx
 * @since 2024/02/06
 * Class MemberModel
 * @package App\Http\Requests
 */
class MemberRequest extends FormRequest
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
            'username' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'membership_code' => 'required',
            'nickname' => 'required',
            'realname' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'source_channel' => 'required',
            'address' => 'required',
            'growth_value' => 'required',
            'integral_value' => 'required',
            'balance_value' => 'required',
            'last_accessed_times' => 'required',
            'last_accessed_ip' => 'required',
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
                'username.required' => '用户名 不能为空。',
                'password.required' => '密码 不能为空。',
                'phone.required' => '手机号 不能为空。',
                'membership_code.required' => '会员码 不能为空。',
                'nickname.required' => '昵称 不能为空。',
                'realname.required' => '真是姓名 不能为空。',
                'birthday.required' => '生日日期 不能为空。',
                'gender.required' => '性别 不能为空。',
                'source_channel.required' => '来源渠道【1.微信公众号| 2.微信小程序 | 3.支付宝小程序 | 4.PC | 5.H5 | 6.APP】 不能为空。',
                'address.required' => '地址 不能为空。',
                'growth_value.required' => '成长值 不能为空。',
                'integral_value.required' => '积分值 不能为空。',
                'balance_value.required' => '余额值 不能为空。',
                'last_accessed_times.required' => '最近登录时间 不能为空。',
                'last_accessed_ip.required' => '最近登录IP 不能为空。',
                ];
    }
}
