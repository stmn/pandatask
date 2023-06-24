<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'max:40', 'string'],
            'last_name' => ['required', 'max:40', 'string'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'public_email' => ['email', 'max:255', 'nullable'],
            'job_title' => ['string', 'max:40', 'nullable'],
            'phone' => ['string', 'max:15', 'nullable']
        ];
    }
}
