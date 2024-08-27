<?php

namespace App\Rules\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniquePhoneRole implements ValidationRule
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function validate($attribute, $value, $fail): void
    {
        $exists = DB::table('users')
            ->where('phone', $value)
            ->where('role', $this->role)
            ->exists();

        if ($exists) {
            $fail('The combination of phone and role has already been taken.');
        }
    }
}
