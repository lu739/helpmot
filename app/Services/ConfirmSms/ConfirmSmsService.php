<?php

namespace App\Services\ConfirmSms;

use App\Models\OnboardingUser;
use Illuminate\Support\Facades\Http;

class ConfirmSmsService
{
    public OnboardingUser $onboardingUser;

    public function setOnboardingUser(OnboardingUser $onboardingUser): ConfirmSmsService
    {
        $this->onboardingUser = $onboardingUser;

        return $this;
    }

    public function sendSmsToOnboardingUser()
    {
        $data = [
            'messages' => [
                [
                    'phone' => $this->onboardingUser->phone,
                    'sender' => 'SMS DUCKOHT',
                    'clientId' => $this->onboardingUser->id,
                    'text' => 'Код подтверждения ' . $this->onboardingUser->phone_code . '. Ваш "HelpMot"',
                ],
            ],
            'login' => env('SMS_LOGIN'),
            'password' => env('SMS_PASSWORD'),
        ];

        return Http::post(env('SMS_ADDRESS'), $data);
    }
}
