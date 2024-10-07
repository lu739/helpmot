<?php

namespace App\Actions\User\ChangePassword;

use App\Models\User;
use App\Actions\User\ChangePassword\Dto\RefreshPasswordUserDto;


class RefreshPasswordUserAction {
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
