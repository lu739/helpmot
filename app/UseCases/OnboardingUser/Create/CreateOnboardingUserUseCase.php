<?php

namespace App\UseCases\OnboardingUser\Create;

use App\Models\OnboardingUser;
use App\UseCases\OnboardingUser\Create\Dto\CreateOnboardingUserDto;


class CreateOnboardingUserUseCase {
    public function handle(CreateOnboardingUserDto $createOnboardingUserDto): OnboardingUser
    {
        $onboardingUser = new OnboardingUser();
        $onboardingUser->phone = $createOnboardingUserDto->getPhone();
        $onboardingUser->role = $createOnboardingUserDto->getRole();
        $onboardingUser->name = $createOnboardingUserDto->getName();
        $onboardingUser->password = $createOnboardingUserDto->getPassword();
        $onboardingUser->phone_code = $createOnboardingUserDto->getPhoneCode();
        $onboardingUser->phone_code_datetime = $createOnboardingUserDto->getPhoneCodeDatetime() ?? now()->format('Y-m-d H:i:s');

        $onboardingUser->save();

        return $onboardingUser;
    }
}
