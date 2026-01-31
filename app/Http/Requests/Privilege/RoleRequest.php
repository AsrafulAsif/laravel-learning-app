<?php

namespace App\Http\Requests\Privilege;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        $roleId = $this->route('role_id');

        return [
            'role_name' => [
                'required',
                'string',
                'max:255',
                $roleId
                    ? Rule::unique('roles', 'role_name')->ignore($roleId)
                    : 'unique:roles,role_name',
            ],
            'role_display_name' =>  $roleId ? 'nullable|string|max:255' : 'required|string|max:255',
            'role_description' => 'nullable|string|max:1000',
        ];
    }
}
