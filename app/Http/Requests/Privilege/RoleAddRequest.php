<?php

namespace App\Http\Requests\Privilege;

use Illuminate\Foundation\Http\FormRequest;

class RoleAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_name' => 'required|string|max:255|unique:roles,role_name',
            'role_display_name' => 'required|string|max:255',
            'role_description' => 'nullable|string|max:1000',
        ];
    }
}
