<?php

namespace App\UseCases\OnboardingUser\Create;

use App\Models\OnboardingUser;
use App\UseCases\OnboardingUser\Create\Dto\CreateOnboardingUserDto;


class CreateOnboardingUserUseCase {
    public function handle(CreateOnboardingUserDto $createOnboardingUserDto): OnboardingUser
    {
        $onboardingUser = new OnboardingUser();
        $onboardingUser->id = $createOnboardingUserDto->getId();
        $onboardingUser->phone = $createOnboardingUserDto->getPhone();
        $onboardingUser->role = $createOnboardingUserDto->getRole();
        $onboardingUser->name = $createOnboardingUserDto->getName();
        $onboardingUser->password = $createOnboardingUserDto->getPassword();
        $onboardingUser->phone_code = $createOnboardingUserDto->getPhoneСode();
        $onboardingUser->phone_code_datetime = $createOnboardingUserDto->getPhoneСodeDatetime();

        $onboardingUser->save();

        return $onboardingUser;
    }
}
