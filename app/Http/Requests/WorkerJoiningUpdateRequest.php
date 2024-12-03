<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WorkerJoiningUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $worker = auth()->guard('worker')->user();
        return [
            "worker_ResidenceNum" => "required|integer",
            "worker_NationalID" =>"required|integer",
            "worker_Fname" => "required",
            "worker_Lname" => "required",
            'worker_Email' => 'required|max:200|email|unique:Worker,worker_Email,'.$worker->worker_ID.',worker_ID',
            "worker_Phone1" => "required",
            "worker_Phone2" => "nullable",
            "worker_Occupation" => "required",
            "worker_Skill" => "nullable",
            "worker_Experience" => "nullable",
            "worker_accountNum" => "nullable",
            "worker_Iban" => "nullable",
            "password" => "nullable|min:8|confirmed",
        ];
    }
}
