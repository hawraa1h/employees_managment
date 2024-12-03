<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    public function rules()
    {
        return [
            "cus_Fname" => "required|max:200",
            "cus_Lname" =>"required|max:200",
            "cus_Phone" => "required|numeric",
            'cus_Email' => 'required|max:200|email|unique:Customer,cus_Email',
            "cus_Address" => "required",
            "password" => "required|confirmed",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
       session()->flash('register_fail', 'register fails');
    }
}
