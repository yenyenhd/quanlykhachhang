<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestRole extends FormRequest
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
            'name'=>'required|unique:roles,name',
            'guard_name' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' =>'Tên vai trò không được để trống!',
            'name.unique' => 'Tên vai trò đã tồn tại',
            'guard_name.required' =>'Guard name không được để trống!',

        ];
    }
        
}
