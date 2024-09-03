<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiRequest;
use App\Rules\User\UniquePhoneRole;

class RefreshPasswordRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'digits:11',  'exists:users,phone', new UniquePhoneRole($this->role)],
            'new_password' => [
                'required',
                'min:8',
                'regex:/[A-Za-z]/',
                'regex:/[0-9]/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('validation.custom.attribute-name.phone_required'),
            'phone.digits' => __('validation.custom.attribute-name.phone_format'),
            'phone.exists' => __('validation.custom.attribute-name.phone_exists'),
            'new_password.required' => __('validation.custom.attribute-name.password_required'),
            'new_password.min' => __('validation.custom.attribute-name.password_min'),
            'new_password.regex' => __('validation.custom.attribute-name.password_regex'),

        ];
    }
}
