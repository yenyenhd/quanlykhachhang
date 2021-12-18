<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class RequestCustomer extends FormRequest
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
            'name'=>'required',
            'phone'=>'required|numeric',
            'email'=>'required|email',
            'address'=>'required',
            'birthday'=>'required|date_format:Y-m-d',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'Bạn chưa nhập họ tên',
            'phone.required'=>'Bạn chưa nhập số điện thoại',
            'phone.numeric'=>'Số điện thoại không đúng định dạng',
            'email.required'=>'Bạn chưa nhập email',
            'email.email'=>'Email không đúng định dạng',
            'address.required'=>'Bạn chưa nhập địa chỉ',
            'birthday.required'=>'Bạn chưa nhập ngày sinh',
            'birthday.date_format'=>'Ngày sinh không đúng định dạng',

        ];
    }
}
