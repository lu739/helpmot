<?php

namespace App\Http\Requests\Api\User;

use App\Enum\UserRole;
use App\Http\Requests\Api\ApiRequest;
use App\Rules\User\CheckExistsOnboardingPhoneRole;
use Illuminate\Validation\Rules\Enum;

class RefreshCodeOnboardingUserRequest extends ApiRequest
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
            'phone' => ['required', 'digits:11',  'exists:onboarding_users,phone', new CheckExistsOnboardingPhoneRole($this->role)],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('validation.custom.attribute-name.phone_required'),
            'phone.digits' => __('validation.custom.attribute-name.phone_format'),
            'phone.exists' => __('validation.custom.attribute-name.phone_exists'),
            'role.required' => __('validation.custom.attribute-name.role_required'),
            'role.enum' => __('validation.custom.attribute-name.role_enum'),
        ];
    }
}
