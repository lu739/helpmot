<?php

namespace App\Http\Requests\Api\User;

use App\Enum\UserRole;
use App\Http\Requests\Api\ApiRequest;
use App\Models\OnboardingUser;
use App\Rules\User\UniquePhoneRole;
use Illuminate\Validation\Rules\Enum;

class OnboardingRequest extends ApiRequest
{
    public function prepareForValidation(): void
    {
        $onboardingUser = OnboardingUser::query()
            ->where('phone', $this->phone)
            ->first();

        $this->merge([
            'role'      => $this->role ?? $onboardingUser->role ?? UserRole::CLIENT->value,
        ]);
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
            'role' => ['required', new Enum(UserRole::class)],
            'name' => ['nullable', 'string'],
            'password' => [
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
            'password.required' => __('validation.custom.attribute-name.password_required'),
            'password.min' => __('validation.custom.attribute-name.password_min'),
            'password.regex' => __('validation.custom.attribute-name.password_regex'),
        ];
    }
}
