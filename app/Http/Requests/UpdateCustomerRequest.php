<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function rules()
    {
        $customer = auth()->guard('customer')->user();

        return [
            "cus_Fname" => "required|max:200",
            "cus_Lname" =>"required|max:200",
            "cus_Phone" => "required|numeric",
            'cus_Email' => 'required|max:200|email|unique:Customer,cus_Email,'.$customer->cus_ID.',cus_ID',
            "cus_Address" => "required",
            "password" => "nullable|min:5|confirmed",
        ];
    }
}
