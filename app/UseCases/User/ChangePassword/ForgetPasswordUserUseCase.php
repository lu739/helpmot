<?php

namespace App\UseCases\User\ChangePassword;

use App\Models\User;
use App\UseCases\User\ChangePassword\Dto\ForgetPasswordUserDto;


class ForgetPasswordUserUseCase {
    public function handle(ForgetPasswordUserDto $sendPhoneCodeUserDto): User
    {
        $user = User::query()
            ->where('phone', $sendPhoneCodeUserDto->getPhone())
            ->where('role', $sendPhoneCodeUserDto->getRole()->value)
            ->first();

        $user->update([
            'phone_code' => $sendPhoneCodeUserDto->getPhoneCode(),
            'new_password' => $sendPhoneCodeUserDto->getNewPassword(),
            'phone_code_datetime' => $sendPhoneCodeUserDto->getPhoneCodeDatetime() ?? now()->format('Y-m-d H:i:s'),
        ]);

        $user->refresh();

        return $user;
    }
}
