<?php

namespace App\UseCases\User\ChangePassword;

use App\Models\User;
use App\UseCases\User\ChangePassword\Dto\RefreshPasswordUserDto;


class RefreshPasswordUserUseCase {
    public function handle(RefreshPasswordUserDto $refreshPasswordUserDto): User
    {
        $user = User::query()
            ->where('id', $refreshPasswordUserDto->getId())
            ->first();

        $user->update([
            'password' => $refreshPasswordUserDto->getNewPassword(),
            'phone_code' => null,
            'phone_code_datetime' => null,
            'new_password' => null,
        ]);

        return $user;
    }
}
