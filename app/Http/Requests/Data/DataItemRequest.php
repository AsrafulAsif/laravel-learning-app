<?php

namespace App\Http\Requests\Data;

use Illuminate\Foundation\Http\FormRequest;

class DataItemRequest extends FormRequest
{

    public function rules(): array
    {
        $data_id = $this->route('data_id');
        return [
            'name' => $data_id ? 'nullable|string' : 'required|string',
            'age' => $data_id ? 'nullable|string' : 'required|string',
        ];
    }
}
