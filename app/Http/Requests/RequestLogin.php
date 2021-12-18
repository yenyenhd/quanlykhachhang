<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestLogin extends FormRequest
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
            'username'=>'required',
            'password'=>'required|min:6|max:32',
        ];
    }
    public function messages()
    {
        return [
            'username.required'=>'Bạn chưa nhập username',
            'password.required'=>'Bạn chưa nhập password',
            'password.min'=>'Password phải có độ dài ít nhất 6 ký tự',
            'password.max'=>'Password phải có độ dài tối đa 32 ký tự',
        ];
    }
}
