<?php

namespace App\Http\Requests\Privilege;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    public function rules(): array
    {
        $permissionId = $this->route('permission_id');

        return [
            'permission_name' => [
                'required',
                'string',
                'max:255',
                $permissionId
                    ? Rule::unique('permissions', 'permission_name')->ignore($permissionId)
                    : 'unique:permissions,permission_name',
            ],
            'permission_display_name' => $permissionId ? 'nullable|string|max:255' : 'required|string|max:255',
            'permission_description' => 'nullable|string|max:1000',
            'controller_name' => $permissionId ? 'nullable|string|max:255' : 'required|string|max:255',
            'api_url' => $permissionId ? 'nullable|string|max:255' : 'required|string|max:255|unique:permissions,api_url',
            'method_name' => $permissionId ? 'nullable|string|max:255' :  'required|string|max:255',
        ];
    }
}
