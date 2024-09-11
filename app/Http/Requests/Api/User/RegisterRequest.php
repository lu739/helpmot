<?php

namespace App\Http\Requests\Api\User;

use App\Enum\UserRole;
use App\Http\Requests\Api\ApiRequest;
use App\Models\OnboardingUser;
use App\Rules\User\UniquePhoneRole;
use Illuminate\Validation\Rules\Enum;

class RegisterRequest extends ApiRequest
{
    public function prepareForValidation(): void
    {
        $onboardingUser = OnboardingUser::query()
            ->where('phone', $this->phone)
            ->where('id', $this->onboarding_id)
            ->first();

        if ($onboardingUser) {
            $this->merge([
                'role' => $onboardingUser->role,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'digits:11', new UniquePhoneRole($this->role)],
            'onboarding_id' => ['required', 'integer', 'exists:onboarding_users,id'],
            'phone_code' => ['required', 'digits:6'],
            'role' => ['required', new Enum(UserRole::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => __('validation.custom.attribute-name.phone_required'),
            'phone.digits' => __('validation.custom.attribute-name.phone_format'),
            'phone_code.required' => __('validation.custom.attribute-name.phone_code_required'),
            'phone_code.digits' => __('validation.custom.attribute-name.phone_code_format'),
            'role.required' => __('validation.custom.attribute-name.role_required'),
            'role.enum' => __('validation.custom.attribute-name.role_enum'),
            'onboarding_id.exists' => __('exceptions.onboarding_user_found_error'),
        ];
    }
}
