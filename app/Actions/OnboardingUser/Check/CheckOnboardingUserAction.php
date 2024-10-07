<?php

namespace App\Actions\OnboardingUser\Check;

use App\Models\OnboardingUser;
use App\Actions\OnboardingUser\Check\Dto\CheckOnboardingUserDto;
use Illuminate\Http\JsonResponse;


class CheckOnboardingUserAction {
    public function handle(CheckOnboardingUserDto $checkOnboardingUserDto): OnboardingUser|JsonResponse
    {
        $onboardingUser = OnboardingUser::query()
            ->where('phone', $checkOnboardingUserDto->getPhone())
            ->when($checkOnboardingUserDto->getRole(), function ($query) use ($checkOnboardingUserDto) {
                $query->where('role', $checkOnboardingUserDto->getRole());
            })
            ->when($checkOnboardingUserDto->getOnboardingId(), function ($query) use ($checkOnboardingUserDto) {
                $query->where('id', $checkOnboardingUserDto->getOnboardingId());
            })
            ->first();

        if ($checkOnboardingUserDto->getCaseType() === 'refresh_token') {
            if (!$onboardingUser) {
                return responseFailed(404, __('exceptions.user_not_found'));
            }
            if ($onboardingUser->user) {
                return responseFailed(403, __('exceptions.user_already_exists'));
            }
            if (isset($onboardingUser->phone_code) && isset($onboardingUser->phone_code_datetime) && !$onboardingUser->isCodeExpired()) {
                return responseFailed(403, __('exceptions.user_code_not_expired'));
            }
        }

        if ($checkOnboardingUserDto->getCaseType() === 'register') {
            if (!$onboardingUser) {
                return responseFailed(404, __('exceptions.onboarding_user_found_error'));
            }
            if ($onboardingUser['phone_code'] != $checkOnboardingUserDto->getPhoneCode()) {
                return responseFailed(404, __('exceptions.phone_code_error'));
            }
            if ($onboardingUser->isCodeExpired()) {
                return responseFailed(404, __('exceptions.phone_code_expired_error'));
            }
        }

        return $onboardingUser;
    }
}
