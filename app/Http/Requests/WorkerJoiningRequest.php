<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use App\Rules\SaudiNationalId;
use Illuminate\Foundation\Http\FormRequest;

class WorkerJoiningRequest extends FormRequest
{
    public function rules()
    {
        $pattern = '/^((\+|00)?966[\s-]?)?((13[\s-]?\d{2})|(5[\s-]?[0-9]{1}(\s?[\d]{7})))$/';

        return [
            "worker_ResidenceNum" => "required|integer",
            'worker_NationalID' => ['required', new SaudiNationalId],
            "worker_Fname" => "required",
            "worker_Lname" => "required",
            'worker_Email' => 'required|max:200|email|unique:Worker,worker_Email',
            "worker_Phone1" =>['required', new PhoneNumber],
            "worker_Phone2" =>['nullable', new PhoneNumber],
            "worker_Occupation" => "required",
            "worker_Skill" => "nullable",
            "worker_Experience" => "nullable",
            "worker_accountNum" => "nullable",
            "worker_Iban" => "nullable",
            "password" => "required|confirmed",
        ];
    }
}
