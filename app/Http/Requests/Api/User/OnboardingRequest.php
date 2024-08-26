<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;

class OnboardingRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'digits:11'],
            'name' => ['nullable', 'string'],
            'password' => ['required'],
        ];
    }
}
