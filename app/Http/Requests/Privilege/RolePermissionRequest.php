<?php

namespace App\Http\Requests\Privilege;

use Illuminate\Foundation\Http\FormRequest;

class RolePermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_id' => 'required | exists:roles,id',
            'permission_id' => 'required | exists:permissions,id',
        ];
    }
}
