<?php

namespace App\Http\Requests\Api\User;

use App\Enum\UserRole;
use App\Http\Requests\Api\ApiRequest;
use App\Rules\User\CheckExistsPhoneRole;
use Illuminate\Validation\Rules\Enum;

class ForgetPasswordRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', new Enum(UserRole::class)],
            'phone' => ['required', 'digits:11',  'exists:users,phone', new CheckExistsPhoneRole($this->role)],
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
            'role.required' => __('validation.custom.attribute-name.role_required'),
            'role.enum' => __('validation.custom.attribute-name.role_enum'),
        ];
    }
}
