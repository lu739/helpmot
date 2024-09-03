<?php

namespace App\Services\ConfirmSms;

use App\Services\ConfirmSms\Interfaces\SmsUserInterface;
use Illuminate\Support\Facades\Http;

class ConfirmSmsService
{
    public SmsUserInterface $smsUser;

    public function setSmsUser(SmsUserInterface $smsUser): ConfirmSmsService
    {
        $this->smsUser = $smsUser;

        return $this;
    }

    public function sendSmsToUser()
    {
        $data = [
            'messages' => [
                [
                    'phone' => $this->smsUser->getPhone(),
                    'sender' => 'SMS DUCKOHT',
                    'clientId' => $this->smsUser->getId(),
                    'text' => 'Код подтверждения ' . $this->smsUser->getPhoneCode() . '. Ваш "HelpMot"',
                ],
            ],
            'login' => env('SMS_LOGIN'),
            'password' => env('SMS_PASSWORD'),
        ];

        return Http::post(env('SMS_ADDRESS'), $data);
    }
}
