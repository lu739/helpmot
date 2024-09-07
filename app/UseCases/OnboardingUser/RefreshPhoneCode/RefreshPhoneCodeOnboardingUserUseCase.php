<?php

namespace App\UseCases\OnboardingUser\RefreshPhoneCode;

use App\Models\OnboardingUser;
use App\UseCases\OnboardingUser\RefreshPhoneCode\Dto\RefreshPhoneCodeOnboardingUserDto;

class RefreshPhoneCodeOnboardingUserUseCase {
    public function handle(RefreshPhoneCodeOnboardingUserDto $refreshPhoneCodeOnboardingUserDto): OnboardingUser
    {
        $user = OnboardingUser::query()
            ->where('id', $refreshPhoneCodeOnboardingUserDto->getId())
            ->first();

        $user->update([
            'phone_code' => $refreshPhoneCodeOnboardingUserDto->getPhoneCode(),
            'phone_code_datetime' => $refreshPhoneCodeOnboardingUserDto->getPhoneCodeDatetime() ?? now()->format('Y-m-d H:i:s'),
        ]);

        $user->refresh();

        return $user;
    }
}
