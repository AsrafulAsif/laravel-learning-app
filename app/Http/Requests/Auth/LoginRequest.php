<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required',],
            'password' => ['required', 'string', 'min:4'],
        ];
    }

}
