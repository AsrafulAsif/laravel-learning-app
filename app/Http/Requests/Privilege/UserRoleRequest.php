<?php

namespace App\Http\Requests\Privilege;

use Illuminate\Foundation\Http\FormRequest;

class UserRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ];
    }
}
