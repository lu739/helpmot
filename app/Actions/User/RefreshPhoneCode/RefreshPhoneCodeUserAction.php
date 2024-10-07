<?php

namespace App\Actions\User\RefreshPhoneCode;

use App\Actions\User\RefreshPhoneCode\Dto\RefreshPhoneCodeUserDto;
use App\Models\OnboardingUser;
use App\Models\User;

class RefreshPhoneCodeUserAction {
    public function handle(RefreshPhoneCodeUserDto $refreshPhoneCodeUserDto): User|OnboardingUser
    {
        $user = $refreshPhoneCodeUserDto->getModel()::query()
            ->where('id', $refreshPhoneCodeUserDto->getId())
            ->first();

        $user->update([
            'phone_code' => $refreshPhoneCodeUserDto->getPhoneCode(),
            'phone_code_datetime' => $refreshPhoneCodeUserDto->getPhoneCodeDatetime() ?? now()->format('Y-m-d H:i:s'),
        ]);

        $user->refresh();

        return $user;
    }
}
