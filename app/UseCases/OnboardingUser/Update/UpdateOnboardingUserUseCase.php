<?php

namespace App\UseCases\OnboardingUser\Update;

use App\Models\OnboardingUser;
use App\UseCases\OnboardingUser\Update\Dto\UpdateOnboardingUserDto;


class UpdateOnboardingUserUseCase {
    public function handle(UpdateOnboardingUserDto $updateOnboardingUserDto): OnboardingUser
    {
        $onboardingUser = OnboardingUser::query()->where([
            'id' => $updateOnboardingUserDto->getId(),
        ])->first();

        $onboardingUser->update([
            'name' => $updateOnboardingUserDto->getName() ?? $onboardingUser->name,
            'password' => $updateOnboardingUserDto->getPassword(),
            'phone_code' => $updateOnboardingUserDto->getPhoneCode() ?? $onboardingUser->phone_code,
            'phone_code_datetime' => $updateOnboardingUserDto->getPhoneCodeDatetime() ?? $onboardingUser->phone_code_datetime,
        ]);

        $onboardingUser->refresh();

        return $onboardingUser;
    }
}
