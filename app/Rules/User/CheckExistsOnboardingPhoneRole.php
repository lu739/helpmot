<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CheckExistsOnboardingPhoneRole implements ValidationRule
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function validate($attribute, $value, $fail): void
    {
        $exists = DB::table('onboarding_users')
            ->where($attribute, $value)
            ->where('role', $this->role)
            ->exists();

        if (!$exists) {
            $fail(__('exceptions.onboarding_user_found_error'));
        }
    }
}
