<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'statuses' => 'array',
            'priorities' => 'array',
            'custom_fields' => 'array',
            'custom_fields.*.key' => 'required|distinct|alpha_dash',
            'custom_fields.*.label' => 'required',
            'custom_fields.*.type' => 'required|in:text,number,date,select',
            'custom_fields.*.options' => 'required_if:custom_fields.*.type,select|array',
        ];
    }

    public function messages(): array
    {
        return [
            'custom_fields.*.*.required' => 'Required',
            'custom_fields.*.*.distinct' => 'Duplicate',
            'custom_fields.*.*.alpha_dash' => 'Wrong format',
        ];
    }
}
