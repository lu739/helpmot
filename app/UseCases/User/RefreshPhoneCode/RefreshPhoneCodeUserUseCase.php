<?php

namespace App\UseCases\User\RefreshPhoneCode;

use App\Models\User;
use App\UseCases\User\RefreshPhoneCode\Dto\RefreshPhoneCodeUserDto;

class RefreshPhoneCodeUserUseCase {
    public function handle(RefreshPhoneCodeUserDto $refreshPhoneCodeUserDto): User
    {
        $user = User::query()
            ->where('id', $refreshPhoneCodeUserDto->getId())
            ->first();

        $user->update([
            'phone_code' => $refreshPhoneCodeUserDto->getPhoneCode(),
            'phone_code_datetime' => $refreshPhoneCodeUserDto->getPhoneCodeDatetime() ?? now()->format('Y-m-d H:i:s'),
        ]);

        $user->refresh();

        return $user;
    }
}
