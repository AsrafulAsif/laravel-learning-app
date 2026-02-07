<?php

namespace App\Http\Requests\Data;

use Illuminate\Foundation\Http\FormRequest;

class WorkFlowRequest extends FormRequest
{
    public function rules(): array
    {
        $workflow_id = $this->route('workflow_id') !== null;

        return [
            'name' => $workflow_id ? ['sometimes', 'string', 'max:255', 'unique:workflow_names,name']
                : ['required', 'string', 'max:255', 'unique:workflow_names,name'],

            'description' => $workflow_id ? ['sometimes', 'string', 'max:255']
                : ['required', 'string', 'max:255'],

            'roles' => $workflow_id ? ['sometimes', 'array', 'min:1']
                : 'required|array|min:1',
            'roles.*' => 'required|string|distinct',
        ];
    }
}
