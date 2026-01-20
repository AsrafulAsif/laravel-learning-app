<?php

namespace App\Http\Requests\Privilege;

use Illuminate\Foundation\Http\FormRequest;

class PermissionAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'permission_name' => 'required|string|max:255|unique:permissions,permission_name',
            'permission_display_name' => 'required|string|max:255',
            'permission_description' => 'nullable|string|max:1000',
            'controller_name' => 'required|string|max:255',
        ];
    }
}
